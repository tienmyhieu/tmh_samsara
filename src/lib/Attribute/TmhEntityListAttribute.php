<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhEntityListAttribute implements TmhAttribute
{
    private TmhAttributeFactory $attributeFactory;
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhAttributeFactory $attributeFactory, TmhEntityAttribute $entityAttribute)
    {
        $this->attributeFactory = $attributeFactory;
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('entity_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('entity', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        return array_map(function($rawListItem) {
            $extraFields = [$rawListItem['type'] => $rawListItem['entity']];
            $rawListItem = array_merge($extraFields, $rawListItem);
            return $this->attributeFactory->create($rawListItem['type'])->create($rawListItem);
        }, $rawListItems);
    }
}
