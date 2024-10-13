<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhTextGroupAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $rawListItems = $this->entityAttribute->items('text_group', $entity['text_group']);
        $translation = implode(' - ', $this->transformTextGroupItems($rawListItems));
        return [
            'entity_type' => $entity['type'],
            'translation' => $translation
        ];
    }

    private function diameter(string $key, string $diameter): string
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $key);
        $millimeters = $this->entityAttribute->attribute('key_value_key', 'gmd8hho2');
        return $keyValueKey['translation'] . ': ' . $diameter . $millimeters['translation'];
    }

    private function page(string $key, string $page): string
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $key);
        return $keyValueKey['translation'] . ': ' . $page;
    }

    private function transformTextGroupItems(array $textGroupItems): array
    {
        $items = [];
        foreach ($textGroupItems as $textGroupItem) {
            $items[] = match($textGroupItem['type']) {
                'diameter' => $this->diameter($textGroupItem['key'] , $textGroupItem['value']),
                'page' => $this->page($textGroupItem['key'], $textGroupItem['value']),
                'year' => $this->year($textGroupItem['key'], $textGroupItem['value']),
                default => $textGroupItem['translation']
            };
        }
        return $items;
    }

    private function year(string $key, string $year): string
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $key);
        return $keyValueKey['translation'] . ': ' . $year;
    }
}
