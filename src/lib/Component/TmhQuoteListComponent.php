<?php

namespace lib\Component;

use lib\TmhDatabase;
use lib\TmhStructure;

class TmhQuoteListComponent implements TmhComponent
{
    private TmhDatabase $database;
    private TmhStructure $structure;

    public function __construct(TmhDatabase $database, TmhStructure $structure)
    {
        $this->database = $database;
        $this->structure = $structure;
    }

    public function get(array $entity): array
    {
        $list = $this->structure->entity('quote_list', $entity['targetUuid']);
        $rawListItems = $this->structure->listItems('quote', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'writing_mode' => $list['writing_mode'],
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        $transformed = [];
        foreach ($rawListItems as $rawListItem) {
            $transformedItem = $rawListItem['value'];
            if ($rawListItem['translate'] == '1') {
                $transformedItem = $rawListItem['translation'];
            }
            $transformed[] = $transformedItem;
        }
        return $transformed;
    }
}
