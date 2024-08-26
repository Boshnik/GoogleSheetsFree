<?php

class GoogleSheetsExportCopyProcessor extends modObjectGetProcessor
{

    public $classKey = GoogleSheetsExport::class;
    public $objectType = 'gs_object';
    public $languageTopics = ['googlesheets'];

    public function cleanup()
    {
        if (!$this->object) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_nfs'));
        }

        $array = $this->object->toArray();
        $array['menuindex'] = $this->modx->getCount($this->classKey);

        $newObject = $this->modx->newObject($this->classKey);
        $newObject->fromArray($array, '', false, true);
        if (!$newObject->save()) {
            $this->failure('copy error', $array);
        }

        return $this->success('', $this->object->toArray());
    }

}

return 'GoogleSheetsExportCopyProcessor';