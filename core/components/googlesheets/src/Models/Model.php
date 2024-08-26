<?php

namespace Boshnik\GoogleSheets\Models;

use Boshnik\GoogleSheets\Traits\Query;
use Boshnik\GoogleSheets\Traits\Helper;

abstract class Model
{
    use Query;
    use Helper;

    /** @var \modX $modx */
    public \modX $modx;

    public $object;

    public array $defaultFields = [];

    function __construct($modx, $object)
    {
        $this->modx = $modx;
        $this->object = $object;

        if (isset($this->defaultFields['template'])) {
            $this->defaultFields['template'] = $this->modx->getOption('default_template');
        }
    }

    public function getModelData(array $properties)
    {
        $values = [];
        if (!empty($this->object->where)) {
            $where = json_decode($this->object->where, true);
            $where = $where[0];
        }

        // Event: gsOnBeforeExportValues
        $this->modx->invokeEvent('gsOnBeforeExportValues', [
            'values' => &$values,
            'object' => $this->object,
            'properties' => $properties,
        ]);

        // fields
        $fields = $this->getObjectFields($this->object->fields);
        $properties['fields'] = array_keys($fields);

        $values = array_merge(
            $values,
            $this->getTableValues($this->classKey, $where ?? [], $properties)
        );
        $values = $this->getCompleteModelData($values);

        return $this->modx->error->success('Success', [
            'values' => $values,
            'count' => count($values),
            'total' => $this->modx->getCount($this->classKey, $where),
        ]);
    }

    public function getCompleteModelData($values)
    {
        return $values;
    }
}