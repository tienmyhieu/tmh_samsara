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
        $afterListItems = $this->transformDynamicListItems($list['after']);
        $beforeListItems = $this->transformDynamicListItems($list['before']);
        $mainListItems = $this->transformListItems($rawListItems);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => array_merge($beforeListItems, $mainListItems, $afterListItems)]
        ];
    }

    private function transformDynamicListItems(array $dynamicListItems): array
    {
        $listItems = [];
        foreach ($dynamicListItems as $dynamicKey => $dynamicValue) {
            $keyValueKey = $this->structure->entity('key_value_key', $dynamicKey);
            $listItems[] = ['key' => $keyValueKey['translation'], 'value' => $dynamicValue, 'type' => 'text'];
        }
        return $listItems;
    }

    private function transformListItems(array $rawListItems): array
    {
        $listItems = [];
        foreach ($rawListItems as $rawListItem) {
            $type = 'text';
            $keyValueKey = $this->structure->entity('key_value_key', $rawListItem['key']);
            $keyValueValue = $this->structure->entity('key_value_value', $rawListItem['value']);
            $value = $keyValueValue['value'];
            if ($keyValueValue['translate'] == '1') {
                $value = $keyValueValue['translation'];
            }
            if (0 < strlen($keyValueValue['route'])) {
                $type = 'route';
                $value = $this->route->routeEntityByKey($keyValueValue['route']);
                if (0 < strlen($keyValueValue['translation'])) {
                    $innerHtml = $keyValueValue['translation'];
                    if (0 < strlen($keyValueValue['identifier'])) {
                        $innerHtml = str_replace('||identifier||', ' ' . $keyValueValue['identifier'], $innerHtml);
                    }
                    $value['innerHtml'] = $innerHtml;
                }
            }
            $listItems[] = ['key' => $keyValueKey['translation'], 'value' => $value, 'type' => $type];
        }
        return $listItems;
    }
}
