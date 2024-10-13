<?php

namespace lib\Component;

use lib\TmhRoute;
use lib\TmhStructure;

class TmhKeyValueListComponent implements TmhComponent
{
    private TmhRoute $route;
    private TmhStructure $structure;

    public function __construct(TmhRoute $route, TmhStructure $structure)
    {
        $this->route = $route;
        $this->structure = $structure;
    }

    public function get(array $entity): array
    {
        $list = $this->structure->entity('key_value_list', $entity['targetUuid']);
        $rawListItems = $this->structure->listItems('key_value', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function diameter(array $keyValueValue): string
    {
        $millimeters = $this->structure->entity('key_value_key', 'gmd8hho2');
        return $keyValueValue['value'] . $millimeters['translation'];
    }

    private function identifier(array $keyValueValue): string
    {
        return str_replace('||identifier||', ' ' . $keyValueValue['identifier'], $keyValueValue['translation']);
    }

    private function inscription(array $keyValueValue): string
    {
        return $this->structure->inscription($keyValueValue['targetUuid']);
    }

    private function shadowRoute(array $keyValueValue): array
    {
        $shadowRoute = $this->structure->entity('shadow_route', $keyValueValue['targetUuid']);
        $route = $this->route->routeEntityByKey($shadowRoute['route']);
        $innerHtml = $shadowRoute['translation'];
        $innerHtml = str_replace('||identifier||', ' ' . $shadowRoute['identifier'], $innerHtml);
        return [
            'href' => $route['href'],
            'innerHtml' => $innerHtml,
            'title' => $route['title']
        ];
    }

    private function transformListItem(array $rawListItem): array
    {
        $keyValueKey = $this->structure->entity('key_value_key', $rawListItem['key']);
        $keyValueValue = $this->structure->entity('key_value_value', $rawListItem['value']);
        $key = $keyValueKey['translation'];
        return match($rawListItem['type']) {
            'diameter' => ['key' => $key, 'value' => $this->diameter($keyValueValue), 'type' => 'text'],
            'identifier' => ['key' => $key, 'value' => $this->identifier($keyValueValue), 'type' => 'text'],
            'inscription' => ['key' => $key, 'value' => $this->inscription($keyValueValue), 'type' => 'text'],
            'shadow_route' => ['key' => $key, 'value' => $this->shadowRoute($keyValueValue), 'type' => 'route'],
            'translated' => ['key' => $key, 'value' => $keyValueValue['translation'], 'type' => 'text'],
            'weight' => ['key' => $key, 'value' => $this->weight($keyValueValue), 'type' => 'text'],
            default => ['key' => $key, 'value' => $keyValueValue['value'], 'type' => 'text']
        };
    }

    private function transformListItems(array $rawListItems): array
    {
        return array_map(function($rawListItem) {
            return $this->transformListItem($rawListItem);
        }, $rawListItems);
    }

    private function weight(array $keyValueValue): string
    {
        $grams = $this->structure->entity('key_value_key', 'oh78sh5t');
        return $keyValueValue['value'] . $grams['translation'];
    }
}
