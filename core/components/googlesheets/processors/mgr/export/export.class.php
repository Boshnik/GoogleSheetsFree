<?php

class GoogleSheetsExportExportProcessor extends modObjectGetProcessor
{

    public $classKey = GoogleSheetsExport::class;
    public $objectType = 'gs_object';
    public $languageTopics = ['googlesheets'];


    /**
     * @return false|string
     */
    public function process()
    {
        $className = 'Boshnik\GoogleSheets\Export';
        if (!class_exists($className)) {
            return $this->failure("Class $className not found");
        }
        $modelClass = new $className($this->modx);

        return $modelClass->process($this->object, $this->properties);
    }
}

return 'GoogleSheetsExportExportProcessor';