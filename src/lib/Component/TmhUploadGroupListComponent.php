<?php

namespace lib\Component;

use lib\TmhStructure;

class TmhUploadGroupListComponent implements TmhComponent
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
        $list = $this->structure->entity('upload_group_list', $entity['targetUuid']);
        $rawListItems = $this->structure->listItems('upload_group', $entity['targetUuid']);
        $extraFields = ['documentTitle' => $entity['documentTitle']];
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems, $extraFields)]
        ];
    }

    private function transformListItems(array $rawListItems, array $extraFields): array
    {
        return array_map(function($rawListItem) use ($extraFields) {
            $rawListItem = array_merge($rawListItem, $extraFields);
            return $this->componentFactory->create($rawListItem['type'])->get($rawListItem);
        }, $rawListItems);
    }
}
