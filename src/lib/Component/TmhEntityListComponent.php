<?php

namespace lib\Component;

use lib\TmhStructure;

class TmhEntityListComponent implements TmhComponent
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
        $list = $this->structure->entity('entity_list', $entity['targetUuid']);
        $rawListItems = $this->structure->listItems('entity', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
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
