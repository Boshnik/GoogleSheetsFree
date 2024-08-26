<?php

class GoogleSheetsExportRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = GoogleSheetsExport::class;
    public $objectType = 'gs_object';
    public $languageTopics = ['googlesheets'];

}

return 'GoogleSheetsExportRemoveProcessor';