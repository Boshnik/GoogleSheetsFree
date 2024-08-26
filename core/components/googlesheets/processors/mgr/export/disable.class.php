<?php

class GoogleSheetsExportDisableProcessor extends modObjectUpdateProcessor
{

    public $classKey = GoogleSheetsExport::class;
    public $objectType = 'gs_object';
    public $languageTopics = ['googlesheets'];

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = [
            'published' => 0,
        ];

        return true;
    }
}

return 'GoogleSheetsExportDisableProcessor';
