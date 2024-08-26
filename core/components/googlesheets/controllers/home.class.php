<?php

/**
 * The home manager controller for GoogleSheets.
 *
 */
class GoogleSheetsHomeManagerController extends modExtraManagerController
{
    /** @var GoogleSheets $googlesheets */
    public $googlesheets;

    public $statusAuth = false;
    public $authUrl = '';

    public function initialize()
    {
        if ($this->modx->services instanceof MODX\Revolution\Services\Container) {
            $this->googlesheets = $this->modx->services->get('googlesheets');
        } else {
            $this->googlesheets = $this->modx->getService('googlesheets', 'GoogleSheets', MODX_CORE_PATH . 'components/googlesheets/model/');
        }

        $this->statusAuth = $this->googlesheets->getAuth();
        if (!$this->statusAuth) {
            $this->authUrl = $this->googlesheets->getAuthUrl();
        }

    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['googlesheets:default'];
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('googlesheets');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $cssUrl = $this->googlesheets->config['cssUrl'] . 'mgr/';
        $jsUrl = $this->googlesheets->config['jsUrl'] . 'mgr/';

        $this->addCss($cssUrl . 'main.css');

        $this->addJavascript($jsUrl . 'googlesheets.js');
        $this->addJavascript($jsUrl . 'misc/utils.js');
        $this->addJavascript($jsUrl . 'misc/combo.js');
        $this->addJavascript($jsUrl . 'misc/default.grid.js');
        $this->addJavascript($jsUrl . 'misc/default.window.js');

        // Export
        $this->addJavascript($jsUrl . 'widgets/export/grid.js');
        $this->addJavascript($jsUrl . 'widgets/export/windows.js');

        $this->addJavascript($jsUrl . 'panel/home.js');
        $this->addJavascript($jsUrl . 'page/home.js');

        $config = $this->googlesheets->config;
        $config['statusAuth'] = $this->statusAuth;
        $config['authUrl'] = $this->authUrl;

        $this->addHtml('<script>
            Ext.onReady(() => {
                GoogleSheets.config = ' . json_encode($config) . ';
                MODx.load({ xtype: "gs-page-home"});
            });
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="gs-panel-home-div"></div>';

        return '';
    }
}