<?php

namespace Boshnik\GoogleSheets\Models;

class modResource extends Model
{

    public $classKey = \modResource::class;
    public $tvResourceClassKey = \modTemplateVarResource::class;

    public array $defaultFields = [
        'context_key' => 'web',
        'template' => 1,
    ];

    public $tvPrefix = 'tv.';

    public function getCompleteModelData($values)
    {
        $fields = $this->getObjectFields($this->object->fields);
        if ($tvValues = $this->filterValuesTV($fields)) {
            foreach ($values as &$value) {
                foreach ($tvValues as $tvKey => $tvTitle) {
                    $tvName = str_replace($this->tvPrefix, '', $tvKey);
                    $value[$tvKey] = $this->getTVValue([
                        'name' => $tvName,
                        'contentid' => $value['id']
                    ]);
                }
            }
        }

        return $values;
    }

    public function filterValuesTV($values)
    {
        return array_filter($values, function($key) {
            return strpos($key, $this->tvPrefix) === 0;
        }, ARRAY_FILTER_USE_KEY);
    }

}