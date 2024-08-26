<?php

namespace Boshnik\GoogleSheets;

use GoogleSheetsExport;

class Export extends GoogleSheets
{
    public function process(GoogleSheetsExport $object, array $properties = [])
    {
        $className = sprintf('Boshnik\GoogleSheets\Models\%s', $object->model_class);
        if (!class_exists($className)) {
            return $this->modx->error->failure("Class $className not found");
        }

        $modelClass = new $className($this->modx, $object);
        $responce = $modelClass->getModelData($properties);
        if (!$responce['success']) {
            return $responce;
        }

        $data = $responce['object'];
        $values = $data['values'];

        // Event: gsOnExportValues
        $this->modx->invokeEvent('gsOnExportValues', [
            'values' => &$values,
            'object' => $object,
        ]);

        $values = $this->prepareValues($object, $values);
        $updates = $this->export($object, $values);

        $header = str_contains($object->fields, '==');
        $exported = $updates['updatedRows'] - $header;
        $message = $this->modx->lexicon('gs_exported_success', [
            'total' => $data['total'],
            'exported' => $exported,
        ]);

        return $this->modx->error->success($message, $updates);
    }

    public function prepareValues($object, $values): array
    {
        list ($headers, $fields) = $this->getExportFields($object);

        $values = array_map(function ($item) use($fields) {
            $value = [];
            foreach ($fields as $field) {
                $value[$field] = $item[$field] ?? ' ';
            }

            return $value;
        }, $values);

        // prepare values
        $values = array_map(function ($value) {
            return array_values($value);
        }, $values);

        // Added header
        if (count($headers)) {
            array_unshift($values, $headers);
        }

        return $values;
    }

    /**
     * @param GoogleSheetsExport $object
     * @return array
     */
    public function getExportFields(GoogleSheetsExport $object): array
    {
        $fields = explode(',', $object->fields);
        $headers = array_map(function ($item) {
            $item = explode('==', $item);
            return  count($item) == 2 ? $item[1] : '';
        }, $fields);
        $headers = array_filter($headers, 'trim');

        $fields = array_map(function ($item) {
            return explode('==', $item)[0];
        }, $fields);

        return [$headers,$fields];
    }

    /**
     * @param GoogleSheetsExport $object
     * @param array $values
     * @return array
     */
    public function export(GoogleSheetsExport $object, array $values)
    {
        if ($object->export_type === 'Append') {
            $responce = $this->appendValues($object->spreadsheet_id, $object->range, $values);
            $updates = [
                'updatedRows' => $responce->updates->updatedRows,
                'updatedColumns' => $responce->updates->updatedColumns,
                'updatedCells' => $responce->updates->updatedCells,
            ];
        } else {
            $responce = $this->updateValues($object->spreadsheet_id, $object->range, $values);
            $updates = [
                'updatedRows' => $responce->updatedRows,
                'updatedColumns' => $responce->updatedColumns,
                'updatedCells' => $responce->updatedCells,
            ];
        }

        return $updates;
    }
}