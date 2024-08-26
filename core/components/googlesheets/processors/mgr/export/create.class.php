<?php

use Boshnik\GoogleSheets\Traits\Helper;
use Boshnik\GoogleSheets\Traits\Validate;

class GoogleSheetsExportCreateProcessor extends modObjectCreateProcessor
{
    use Helper;
    use Validate;

    public $classKey = GoogleSheetsExport::class;
    public $objectType = 'gs_object';
    public $languageTopics = ['googlesheets'];


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $spreadsheet = trim($this->properties['spreadsheet']);
        if (!empty($spreadsheet)) {
            $this->setProperty('spreadsheet_id', explode('/', $spreadsheet)[5]);
            $this->setProperty('sheet_id', explode('#gid=', $spreadsheet)[1]);
        }

        return $this->validateImportFields($this->properties);
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray([
            'menuindex' => $this->modx->getCount($this->classKey),
        ]);

        return true;
    }

}

return 'GoogleSheetsExportCreateProcessor';