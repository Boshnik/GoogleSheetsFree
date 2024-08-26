<?php

class GoogleSheetsExportGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = GoogleSheetsExport::class;
    public $objectType = 'gs_object';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = trim($this->properties['query']);
        if ($query) {
            $c->where([
                'desc:LIKE' => "%{$query}%",
                'OR:spreadsheet:LIKE' => "%{$query}%",
                'OR:spreadsheet_id:LIKE' => "%{$query}%",
                'OR:range:LIKE' => "%{$query}%",
                'OR:sheet_id:LIKE' => "%{$query}%",
            ]);
        }

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['actions'] = [];

        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('gs_row_update'),
            'action' => 'updateObject',
            'button' => true,
            'menu' => true,
        ];

        // Copy
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-copy',
            'title' => $this->modx->lexicon('gs_row_copy'),
            'action' => 'copyObject',
            'button' => true,
            'menu' => true,
        ];

        // Export
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-upload action-green',
            'title' => $this->modx->lexicon('gs_export'),
            'action' => 'exportObject',
            'button' => true,
            'menu' => true,
        ];


        // Open list
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-globe action-green',
            'title' => $this->modx->lexicon('gs_sheets_link'),
            'action' => 'openSheet',
            'button' => true,
            'menu' => true,
            'spreadsheet' => $array['spreadsheet']
        ];

        if (!$array['published']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('gs_row_enable'),
                'multiple' => $this->modx->lexicon('gs_rows_enable'),
                'action' => 'enableObject',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('gs_row_disable'),
                'multiple' => $this->modx->lexicon('gs_rows_disable'),
                'action' => 'disableObject',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('gs_row_remove'),
            'multiple' => $this->modx->lexicon('gs_rows_remove'),
            'action' => 'removeObject',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'GoogleSheetsExportGetListProcessor';