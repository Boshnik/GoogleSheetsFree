<?php

namespace Boshnik\GoogleSheets;

use Boshnik\GoogleSheets\Traits\Query;
use modX;
use xPDO;


/**
 * class GoogleSheets
 *
 * https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values#dimension
 *
 * ValueInputOption
 * https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
 */
class GoogleSheets
{
    use Query;

    /** @var modX $modx */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'googlesheets';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'GoogleSheets';

    /**
     * The version
     * @var string $version
     */
    public $version = '1.0.0';

    /**
     * @var array $config
     */
    public $config = [];

    /**
     * @var array $credentials
     */
    public $credentials = [];

    /**
     * @var array $exportOptions
     */
    public $exportOptions = [];

    /**
     * @var $client
     */
    protected $client;

    /**
     * @var string
     */
    public $cacheKey = 'googlesheets_access_token';

    /**
     * @var array
     */
    public $cacheOptions = [
        xPDO::OPT_CACHE_KEY => 'googlesheets',
    ];


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath = MODX_CORE_PATH . 'components/googlesheets/';
        $assetsUrl = MODX_ASSETS_URL . 'components/googlesheets/';

        $modxversion = $this->modx->getVersionData();
        $server_protocol = $this->modx->getOption('server_protocol', null, 'http', true);

        $this->config = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',

            'modxversion' => $modxversion['version'],
            'is_admin' => $this->modx->user->isMember('Administrator'),
            'urlComponent' => $server_protocol . '://' . $_SERVER['HTTP_HOST'] . MODX_MANAGER_URL . '?a=home&namespace=googlesheets'
        ], $config);

        list($this->credentials, $this->exportOptions) = $this->getCredentials();

        $this->modx->addPackage($this->namespace, $this->config['modelPath']);
        $this->modx->lexicon->load("$this->namespace:default");
    }

    /**
     * @param string $action
     * @param array $data
     * @return false
     */
    public function runProcessor($action = '', $data = [])
    {
        if (empty($action)) {
            return false;
        }
        $this->modx->error->reset();
        $processorsPath = !empty($this->config['processorsPath'])
            ? $this->config['processorsPath']
            : MODX_CORE_PATH . 'components/googlesheets/processors/';

        return $this->modx->runProcessor($action, $data, [
            'processors_path' => $processorsPath,
        ]);
    }


    /**
     * get credentials
     * @return array
     */
    public function getCredentials(): array
    {
        return [
            [
                'auth_code' => $this->modx->getOption('googlesheets_auth_code'),
                'client_id' => $this->modx->getOption('googlesheets_client_id'),
                'client_secret' => $this->modx->getOption('googlesheets_client_secret'),
                'access_token' => $this->modx->getOption('googlesheets_access_token'),
                'refresh_token' => $this->modx->getOption('googlesheets_refresh_token'),
                'redirect_uri' => "https://{$_SERVER['HTTP_HOST']}/assets/components/googlesheets/oauth2callback.php",
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            ], [
                'valueInputOption' => $this->modx->getOption('googlesheets_valueinputoption', null, 'RAW', true),
                'majorDimension' => $this->modx->getOption('googlesheets_majordimension', null, 'ROWS', true),
            ]
        ];
    }


    /**
     * set Auth
     * @param string $authCode
     * @return false
     */
    public function setAuth($authCode = '')
    {
        if (empty($authCode)) {
            return false;
        }

        $client = $this->getClient();

        // update token
        $token = $client->fetchAccessTokenWithAuthCode($authCode);

        if (isset($token['error'])) {
            print_r($token);
            return false;
        }

        $this->updateOption('auth_code', $authCode);
        foreach ($token as $name => $value) {
            $this->updateOption($name, $value);
        }

        // cache the token
        $this->modx->cacheManager->set($this->cacheKey, $token, $token['expires_in'] - 60, $this->cacheOptions);

        // redirect to component page
        $this->modx->sendRedirect($this->config['urlComponent']);
    }


    /**
     * reset Auth
     * @param string $authCode
     * @return false
     */
    public function resetAuth($authCode = '')
    {
        if ($authCode !== $this->credentials['auth_code']) {
            return false;
        }

        foreach ($this->credentials as $name => $value) {
            $this->updateOption($name, '');
        }

        // clear cache
        $this->modx->cacheManager->clearCache();

        // redirect to component page
        $this->modx->sendRedirect($this->config['urlComponent']);
    }


    /**
     * @return false|Client
     */
    public function getClient()
    {
        if (empty($this->credentials)) {
            list($this->credentials, $this->exportOptions) = $this->getCredentials();
        }

        try {
            $this->client = new \Google\Client();
            $this->client->setAuthConfig($this->credentials);
            $this->client->setRedirectUri($this->credentials['redirect_uri']);
//            $this->client->useApplicationDefaultCredentials();
            $this->client->addScope(\Google\Service\Drive::DRIVE);
            $this->client->addScope(\Google\Service\Sheets::SPREADSHEETS);
            $this->client->setAccessType('offline');
            $this->client->setPrompt('consent');
            $this->client->setIncludeGrantedScopes(true);

            if ($this->getAuth()) {

                // Checking the token
                $token = $this->modx->cacheManager->get($this->cacheKey, $this->cacheOptions);
                if (!isset($token['access_token']) || empty($token['refresh_token'])) {
                    $token = $this->refreshToken();
                }

                if (isset($token['access_token'])) {
                    $this->client->setAccessToken([
                        'access_token' => $token['access_token'],
                        'refresh_token' => $token['refresh_token'],
                        'expires_in' => $token['expires_in'],
                        'created' => time(),
                    ]);
                }
            }

            return $this->client;
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @return bool
     */
    public function getAuth(): bool
    {
        $auth_code = $this->modx->getOption('googlesheets_auth_code');
        if (empty($auth_code)) {
            return false;
        }
        return true;
    }


    /**
     * @return string
     */
    public function getAuthUrl(): string
    {
        $client = $this->getClient();
        return $client->createAuthUrl();
    }


    /**
     * @return array|\Google_Service_Sheets|string
     */
    public function getService()
    {
        $client = $this->getClient();
        try {
            return new \Google_Service_Sheets($client);
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * refreshToken
     * @return array
     */
    public function refreshToken(): array
    {
        $token = $this->client->refreshToken($this->credentials['refresh_token']);

        if (isset($token['error'])) {
            $this->modx->log(1, 'Error: ' . $token['error_description']);
            return $token;
        }

        $this->updateOption('googlesheets_access_token', $token['access_token']);

        // cache the token
        $this->modx->cacheManager->set($this->cacheKey, $token, $token['expires_in'] - 60, $this->cacheOptions);

        return $token;
    }


    /**
     * @param $title
     * @return array
     */
    public function create($title)
    {
        $service = $this->getService();
        try {
            $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
                'properties' => [
                    'title' => $title
                ]
            ]);
            $spreadsheet = $service->spreadsheets->create($spreadsheet, [
                'fields' => 'spreadsheetId'
            ]);

            return $spreadsheet->spreadsheetId;
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $spreadsheetId
     * @param $sheetId
     * @param $toSpreadsheetId
     * @return array|\Google\Service\Sheets\SheetProperties|string
     */
    public function copyTo($spreadsheetId, $sheetId, $toSpreadsheetId = '')
    {
        $service = $this->getService();
        try {
            $requestBody = new \Google_Service_Sheets_CopySheetToAnotherSpreadsheetRequest([
                'destinationSpreadsheetId' => $toSpreadsheetId ?: $spreadsheetId,
            ]);
            return $service->spreadsheets_sheets->copyTo($spreadsheetId, $sheetId, $requestBody);
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $spreadsheetId
     * @param $range
     * @return array
     */
    public function getValues($spreadsheetId, $range)
    {
        $service = $this->getService();
        try {
            $result = $service->spreadsheets_values->get($spreadsheetId, $range);
            return $result->getValues();
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $spreadsheetId
     * @param $ranges
     * @return array
     */
    public function batchGetValues($spreadsheetId, $ranges)
    {
        $service = $this->getService();
        try {
            $params = ['ranges' => $ranges];
            //execute the request
            $result = $service->spreadsheets_values->batchGet($spreadsheetId, $params);
            $values = [];
            if (count($result->getValueRanges())) {
                foreach ($result->getValueRanges() as $res) {
                    $values[] = $res->values[0];
                }
            }
            return $values;
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $spreadsheetId
     * @param $range
     * @param $valueInputOption
     * @param $values
     * @return array
     */
    public function updateValues($spreadsheetId, $range, $values)
    {
        $service = $this->getService();
        try {
            $body = new \Google_Service_Sheets_ValueRange([
                'values' => $values,
                'majorDimension' => $this->exportOptions['majorDimension'],
            ]);
            $params = [
                'valueInputOption' => $this->exportOptions['valueInputOption'],
            ];
            return $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $spreadsheetId
     * @param $range
     * @param $valueInputOption
     * @param $values
     * @return array
     */
    public function batchUpdateValues($spreadsheetId, $ranges, $values)
    {
        $service = $this->getService();
        try {
            $data = [];
            foreach ($ranges as $idx => $range) {
                $data[] = new \Google_Service_Sheets_ValueRange([
                    'range' => $range,
                    'values' => [$values[$idx]],
                    'majorDimension' => $this->exportOptions['majorDimension'],
                ]);
            }
            $body = new \Google_Service_Sheets_BatchUpdateValuesRequest([
                'valueInputOption' => $this->exportOptions['valueInputOption'],
                'data' => $data
            ]);
            return $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }

    /**
     * @param $spreadsheetId
     * @param $range
     * @param $valueInputOption
     * @param $values
     * @return array
     */
    public function appendValues($spreadsheetId, $range, $values)
    {
        $service = $this->getService();
        try {
            $body = new \Google_Service_Sheets_ValueRange([
                'values' => $values,
                'majorDimension' => $this->exportOptions['majorDimension'],
            ]);
            $params = [
                'valueInputOption' => $this->exportOptions['valueInputOption'],
            ];
            return $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $spreadsheetId
     * @param $title
     * @param $find
     * @param $replacement
     * @return int
     */
    public function batchFindReplaceValue($spreadsheetId, $sheetId, $find, $replacement)
    {
        $service = $this->getService();
        try {
            $requests = [
                new \Google_Service_Sheets_Request([
                    'findReplace' => [
                        'find' => $find,
                        'replacement' => $replacement,
                        'matchCase' => false,
                        'matchEntireCell' => true,
                        'includeFormulas' => false,
                        'sheetId' => $sheetId
                    ]
                ])
            ];
            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => $requests
            ]);
            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);

            return $response->getReplies()[0]->getFindReplace()->getOccurrencesChanged();
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $spreadsheetId
     * @param $range
     * @return array
     */
    public function clearValues($spreadsheetId, $range)
    {
        $service = $this->getService();
        try {
            $requestBody = new \Google_Service_Sheets_ClearValuesRequest();
            return $service->spreadsheets_values->clear($spreadsheetId, $range, $requestBody);
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $spreadsheetId
     * @param $ranges
     * @return array|\Google\Service\Sheets\BatchClearValuesResponse|string
     */
    public function batchClearValues($spreadsheetId, $ranges)
    {
        $service = $this->getService();
        try {
            $requestBody = new \Google_Service_Sheets_BatchClearValuesRequest([
                'ranges' => $ranges
            ]);
            return $service->spreadsheets_values->batchClear($spreadsheetId, $requestBody);
        } catch (\Exception $e) {
            return $this->errorResult($e);
        }
    }


    /**
     * @param $e
     * @return array
     */
    public function errorResult($e): array|string
    {
        $this->modx->log(1, 'Message: ' . $e->getMessage());
        return 'Error message: ' . $e->getMessage();
    }
}