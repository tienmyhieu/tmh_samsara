<?php

namespace lib\Component;

use lib\TmhStructure;

class TmhTextGroupComponent implements TmhComponent
{
    private TmhStructure $structure;

    public function __construct(TmhStructure $structure)
    {
        $this->structure = $structure;
    }

    public function get(array $entity): array
    {
        $rawListItems = $this->structure->items('text_group', $entity['text_group']);
        $translation = str_repeat('&nbsp;', (int)$entity['identifier'] ?? 0);
        $translation .= implode(' - ', $this->transformTextGroupItems($rawListItems));
        return [
            'entity_type' => $entity['type'],
            'translation' => $translation
        ];
    }

    private function diameter(array $keyValueValue): string
    {
        $keyValueKey = $this->structure->entity('key_value_key', 'cxzgs1ox');
        $millimeters = $this->structure->entity('key_value_key', 'gmd8hho2');
        return $keyValueKey['translation'] . ': ' . $keyValueValue['value'] . $millimeters['translation'];
    }

    private function transformTextGroupItems(array $textGroupItems): array
    {
        $items = [];
        foreach ($textGroupItems as $textGroupItem) {
            $items[] = match($textGroupItem['type']) {
                'diameter' => $this->diameter($textGroupItem),
                default => $textGroupItem['translation']
            };
        }
        return $items;
    }
}
