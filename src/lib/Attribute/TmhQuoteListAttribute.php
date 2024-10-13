<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhQuoteListAttribute implements TmhAttribute
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
        $list = $this->entityAttribute->attribute('quote_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('quote', $entity['targetUuid']);
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
            return $this->componentFactory->create($rawListItem['type'])->create($rawListItem);
        }, $rawListItems);
    }
}
