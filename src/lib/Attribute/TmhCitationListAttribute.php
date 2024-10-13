<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhCitationListAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('citation_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('citation', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        $transformed = [];
        $citations = $this->entityAttribute->attributes('citation');
        foreach ($rawListItems as $rawListItem) {
            $citation = $citations[$rawListItem['citation']];
            $translation = str_replace('||page||', $rawListItem['page'], $citation['translation']);
            $transformed[] = str_replace('||plate||', $rawListItem['plate'], $translation);
        }
        return $transformed;
    }
}
