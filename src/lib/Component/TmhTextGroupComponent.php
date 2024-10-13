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
        $textGroup = $this->structure->entity('text_group', $entity['entity']);
        $translation = str_repeat('&nbsp;', (int)$entity['identifier'] ?? 0);
        $translation .= $this->transformTextGroupItems($textGroup['before']);
        $translation .= $this->transformTextGroupItems($textGroup['after']);
        return [
            'entity_type' => $entity['type'],
            'translation' => $translation
        ];
    }

    private function transformTextGroupItems(array $dynamicListItems): string
    {
        $textGroupItems = '';
        foreach ($dynamicListItems as $dynamicKey => $dynamicValue) {
            $keyValueKey = $this->structure->entity('key_value_key', $dynamicKey);
            $textGroupItems .= $keyValueKey['translation'] . ': ' . $dynamicValue . ' ';
        }
        return $textGroupItems;
    }
}
