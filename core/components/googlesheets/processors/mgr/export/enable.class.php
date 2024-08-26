<?php

class GoogleSheetsExportEnableProcessor extends modObjectUpdateProcessor
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
            'published' => 1,
        ];

        return true;
    }

}

return 'GoogleSheetsExportEnableProcessor';
