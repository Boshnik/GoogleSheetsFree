<?php

class GoogleSheetsExportGetProcessor extends modObjectGetProcessor
{
    public $classKey = GoogleSheetsExport::class;
    public $objectType = 'gs_object';
    public $languageTopics = ['googlesheets:default'];

}

return 'GoogleSheetsExportGetProcessor';