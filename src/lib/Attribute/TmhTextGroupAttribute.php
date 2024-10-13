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
        return [
            'entity_type' => $entity['type'],
            'translation' => $this->translateTextGroup($entity)
        ];
    }

    private function translateTextGroup(array $entity): string
    {
        $rawTextGroup = $this->entityAttribute->attribute('text_group', $entity['text_group']);
        $rawListItems = $this->entityAttribute->items('text_group', $entity['text_group']);
        $parts = $this->transformTextGroupItems($rawListItems);
        $imploded = implode(' - ', $parts);
        return match($rawTextGroup['type']) {
            'identified' => $rawTextGroup['identifier'] . '. ' . $imploded,
            default => $imploded
        };
    }

    private function date(string $key, string $date): string
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $key);
        return $keyValueKey['translation'] . ': ' . $date;
    }

    private function diameter(string $key, string $diameter): string
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $key);
        $millimeters = $this->entityAttribute->attribute('key_value_key', 'gmd8hho2');
        return $keyValueKey['translation'] . ': ' . $diameter . $millimeters['translation'];
    }

    private function identifier(string $key, string $identifier): string
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $key);
        return $keyValueKey['translation'] . ': ' . $identifier;
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
                'date' => $this->date($textGroupItem['key'] , $textGroupItem['value']),
                'diameter' => $this->diameter($textGroupItem['key'] , $textGroupItem['value']),
                'identifier' => $this->identifier($textGroupItem['key'] , $textGroupItem['value']),
                'page' => $this->page($textGroupItem['key'], $textGroupItem['value']),
                'weight' => $this->weight($textGroupItem['key'] , $textGroupItem['value']),
                'year' => $this->year($textGroupItem['key'], $textGroupItem['value']),
                default => $textGroupItem['translation']
            };
        }
        return $items;
    }

    private function weight(string $key, string $weight): string
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $key);
        $grams = $this->entityAttribute->attribute('key_value_key', 'oh78sh5t');
        return $keyValueKey['translation'] . ': ' . $weight . $grams['translation'];
    }

    private function year(string $key, string $year): string
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $key);
        return $keyValueKey['translation'] . ': ' . $year;
    }
}
