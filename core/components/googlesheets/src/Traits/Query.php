<?php

namespace Boshnik\GoogleSheets\Traits;

trait Query
{
    public $limit = 5000;
    /**
     * Get columns
     * @return mixed
     */
    public function getColumns()
    {
        $q = $this->modx->prepare("DESCRIBE " . $this->modx->getTableName($this->classKey));
        $q->execute();
        return $q->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param $classKey
     * @param array $where
     * @param array $properties
     * @return mixed
     */
    public function getTableValues($classKey, $where = [], $properties = [])
    {
        $query = $this->modx->newQuery($classKey);
        $query->where($where);
        $query->limit($properties['limit'] ?? $this->limit, $properties['offset'] ?? 0);
        $query->select($this->modx->getSelectColumns($classKey, $classKey, '', $properties['fields'] ?? [], false));
        $query->prepare();
        $query->stmt->execute();

        return $query->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $name
     * @param $value
     */
    public function updateOption($name, $value)
    {
        if ($setting = $this->modx->getObject(\modSystemSetting::class, "googlesheets_$name")) {
            $setting->set('value', $value);
            $setting->save();

            $this->modx->getCacheManager()->refresh([
                'system_settings' => [],
            ]);
        }
    }

    public function create($classKey, $value)
    {
        $object = $this->modx->newObject($classKey);
        $object->fromArray($value);
        if (!$object->save()) {
            return null;
        }

        return $object;
    }

    public function update($object, $value)
    {
        $object->fromArray($value);
        if (!$object->save()) {
            return null;
        }

        return $object;
    }

    public function updateOrCreate($className, $where = [], $values = [])
    {
        $object = $this->modx->getObject($className, $where);
        if ($object) {
            $this->update($object, $values);
        } else {
            $this->create($className, $values);
        }
    }

    public function getTV($name)
    {
        if (empty($name)) {
            return null;
        }

        return $this->modx->getObject(\modTemplateVar::class, ['name' => $name]);
    }

    public function getTVValue($where = [])
    {
        if (empty($where['name'])) {
            return '';
        }

        $classKey = \modTemplateVar::class;
        $tmplvarClassKey = \modTemplateVarResource::class;
        $tmplvarAlias = 'TemplateVarResource';

        foreach ($where as $key => $value) {
            if (in_array($key, ['tmplvarid', 'contentid'])) {
                unset($where[$key]);
                $key = "$tmplvarAlias.$key";
                $where[$key] = $value;
            }
        }
        $properties = ['fields' => ['id', 'name', 'tmplvarid', 'contentid', 'value']];

        $query = $this->modx->newQuery($classKey);
        $query->leftJoin($tmplvarClassKey, $tmplvarAlias, "{$tmplvarAlias}.tmplvarid = {$classKey}.id");
        $query->where($where);
        $query->limit(0,0);
        $selectFields = $this->modx->getSelectColumns($classKey, $classKey, '', $properties['fields'] ?? [], false);
        $tmplvarSelectFields = $this->modx->getSelectColumns($tmplvarClassKey, $tmplvarAlias, '', $properties['fields'] ?? [], false);
        $query->select("{$selectFields}, {$tmplvarSelectFields}");
        $query->prepare();
        $query->stmt->execute();
        $result = $query->stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $result['value'] : '';
    }

}