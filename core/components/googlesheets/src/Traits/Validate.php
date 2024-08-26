<?php

namespace Boshnik\GoogleSheets\Traits;

trait Validate
{
    public function validateImportFields(array $properties)
    {
        $model_class = $properties['model_class'];
        if (!in_array($model_class, ['modResource', 'modUser'])) {
            return true;
        }

        $fields = $this->getObjectFields($properties['fields']);
        if (isset($fields['id'])) {
            return true;
        }

        $this->modx->error->addField('fields', $this->modx->lexicon('gs_field_fields_empty_id'));
        return false;
    }
}