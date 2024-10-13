<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;
use lib\Core\TmhRoute;

class TmhKeyValueListAttribute implements TmhAttribute
{
    private TmhRoute $route;
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhRoute $route, TmhEntityAttribute $entityAttribute)
    {
        $this->route = $route;
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('key_value_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('key_value', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems, $entity['lang'])]
        ];
    }

    private function diameter(array $keyValueValue): string
    {
        $millimeters = $this->entityAttribute->attribute('key_value_key', 'gmd8hho2');
        return $keyValueValue['value'] . $millimeters['translation'];
    }

    private function identifier(array $keyValueValue): string
    {
        return str_replace('||identifier||', ' ' . $keyValueValue['identifier'], $keyValueValue['translation']);
    }

    private function inscription(array $keyValueValue): string
    {
        return $this->entityAttribute->inscription($keyValueValue['targetUuid']);
    }

    private function shadowRoute(array $keyValueValue): array
    {
        $shadowRoute = $this->entityAttribute->attribute('shadow_route', $keyValueValue['targetUuid']);
        $route = $this->route->routeEntityByKey($shadowRoute['route']);
        $innerHtml = $shadowRoute['translation'];
        $innerHtml = str_replace('||identifier||', ' ' . $keyValueValue['value'], $innerHtml);
        return [
            'href' => $route['href'],
            'innerHtml' => $innerHtml,
            'title' => $route['title']
        ];
    }

    private function transformListItem(array $rawListItem, string $language): array
    {
        $keyValueKey = $this->entityAttribute->attribute('key_value_key', $rawListItem['key']);
        $keyValueValue = $this->entityAttribute->attribute('key_value_value', $rawListItem['value']);
        $key = $keyValueKey['translation'];
        $useLanguage = !in_array($language, ['ja', 'zh']);
        $lang = $useLanguage ? 'zh' : '';
        return match($rawListItem['type']) {
            'diameter' => ['key' => $key, 'value' => $this->diameter($keyValueValue), 'type' => 'text'],
            'identifier' => ['key' => $key, 'value' => $this->identifier($keyValueValue), 'type' => 'text'],
            'inscription' => ['key' => $key, 'lang' => $lang, 'value' => $this->inscription($keyValueValue), 'type' => 'text'],
            'shadow_route' => ['key' => $key, 'value' => $this->shadowRoute($keyValueValue), 'type' => 'route'],
            'translated' => ['key' => $key, 'value' => $keyValueValue['translation'], 'type' => 'text'],
            'weight' => ['key' => $key, 'value' => $this->weight($keyValueValue), 'type' => 'text'],
            default => ['key' => $key, 'value' => $keyValueValue['value'], 'type' => 'text']
        };
    }

    private function transformListItems(array $rawListItems, string $language): array
    {
        return array_map(function($rawListItem) use ($language) {
            return $this->transformListItem($rawListItem, $language);
        }, $rawListItems);
    }

    private function weight(array $keyValueValue): string
    {
        $grams = $this->entityAttribute->attribute('key_value_key', 'oh78sh5t');
        return $keyValueValue['value'] . $grams['translation'];
    }
}
