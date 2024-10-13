<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhImageGroupListAttribute implements TmhAttribute
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
        $list = $this->entityAttribute->attribute('image_group_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('image_group', $entity['targetUuid']);
        $translation = str_replace('||identifier||', ' ' . $entity['identifier'], $entity['translation']);
        if (0 == strlen($translation)) {
            $translation = $list['translation'];
        }
        return [
            'component_type' => $entity['type'],
            'translation' => $translation,
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        return array_map(function($rawListItem) {
            return $this->attributeFactory->create($rawListItem['type'])->create($rawListItem);
        }, $rawListItems);
    }
}
