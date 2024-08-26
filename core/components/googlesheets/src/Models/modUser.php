<?php

namespace Boshnik\GoogleSheets\Models;

class modUser extends Model
{
    public $classKey = \modUser::class;
    public $profileClassKey = \modUserProfile::class;

    public function getTableValues($classKey, $where = [], $properties = ['limit' => 0, 'offset' => 0])
    {
        $query = $this->modx->newQuery($classKey);
        $query->leftJoin($this->profileClassKey, $this->profileClassKey, "{$this->profileClassKey}.internalKey = {$classKey}.id");
        $query->where($where);
        $query->limit($properties['limit'] ?? $this->limit, $properties['offset'] ?? 0);
        $selectFields = $this->modx->getSelectColumns($classKey, $classKey, '', $properties['fields'] ?? [], false);
        $profileSelectFields = $this->modx->getSelectColumns($this->profileClassKey, $this->profileClassKey, '', $properties['fields'] ?? [], false);
        $query->select("{$selectFields}, {$profileSelectFields}");
        $query->prepare();
        $query->stmt->execute();

        return $query->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}