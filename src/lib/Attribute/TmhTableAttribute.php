<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhTableAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $table = $this->entityAttribute->attribute('table', $entity['targetUuid']);
        $rawRows = $this->entityAttribute->items('table', $entity['targetUuid']);
        $rows = [];
        foreach ($rawRows as $rowUUid => $rawRow) {
            $rawCells = $this->entityAttribute->items('table_row', $rowUUid);
            $cells = array_map(function ($rawCell) {
                return ['colspan' => $rawCell['colspan'], 'text' => $rawCell['text']];
            }, $rawCells);
            $rows[$rowUUid] = $cells;
        }
        return [
            'entity_type' => $entity['type'],
            'translation' => $table['translation'],
            'list' => ['rows' => $rows]
        ];
    }
}
