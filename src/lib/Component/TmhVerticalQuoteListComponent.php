<?php

namespace lib\Component;

use lib\TmhStructure;

class TmhVerticalQuoteListComponent implements TmhComponent
{
    private TmhComponentFactory $componentFactory;
    private TmhStructure $structure;

    public function __construct(TmhComponentFactory $componentFactory, TmhStructure $structure)
    {
        $this->componentFactory = $componentFactory;
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
        return array_map(function($rawListItem) {
            return $this->componentFactory->create($rawListItem['type'])->get($rawListItem);
        }, $rawListItems);
    }
}
