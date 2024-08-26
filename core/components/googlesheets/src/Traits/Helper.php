<?php

namespace Boshnik\GoogleSheets\Traits;

trait Helper
{
    public function getObjectFields(string $fields): array
    {
        $result = [];
        $pairs = explode(',', $fields);
        foreach ($pairs as $pair) {
            $pair = explode('==', $pair);
            $result[$pair[0]] = $pair[1] ?? $pair[0];
        }

        return $result;
    }
}