<?php

use Boshnik\GoogleSheets\Traits\Helper;
use Boshnik\GoogleSheets\Traits\Validate;

class GoogleSheetsExportUpdateProcessor extends modObjectUpdateProcessor
{
    use Helper;
    use Validate;

    public $classKey = GoogleSheetsExport::class;
    public $objectType = 'gs_object';
    public $languageTopics = ['googlesheets'];

    public function beforeSet()
    {
        return $this->validateImportFields($this->properties);
    }
}

return 'GoogleSheetsExportUpdateProcessor';
