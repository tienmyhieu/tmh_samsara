<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhUploadGroupListAttribute implements TmhAttribute
{
    private TmhAttributeFactory $componentFactory;
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhAttributeFactory $componentFactory, TmhEntityAttribute $entityAttribute)
    {
        $this->componentFactory = $componentFactory;
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('upload_group_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('upload_group', $entity['targetUuid']);
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
            return $this->componentFactory->create($rawListItem['type'])->create($rawListItem);
        }, $rawListItems);
    }
}
