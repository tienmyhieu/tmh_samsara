<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhMeasurementListAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('measurement_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('measurement', $entity['targetUuid']);
        $listItems = $this->transformListItems($rawListItems);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $listItems]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        $transformed = [];
        $average = $this->entityAttribute->attribute('key_value_key', 'mocv7gr3');
        $diameter = $this->entityAttribute->attribute('key_value_key', 'cxzgs1ox');
        $identifier = $this->entityAttribute->attribute('key_value_key', 'oqg4zyxr');
        $weight = $this->entityAttribute->attribute('key_value_key', 'bnauzpzl');
        $keys = [
            'identifier' => $identifier['translation'],
            'diameter' => $diameter['translation'],
            'weight' => $weight['translation']
        ];
        $transformed[] = $keys;
        $totalDiameter = 0.00;
        $totalWeight = 0.00;
        foreach ($rawListItems as $rawListItem) {
            $measurement = $this->entityAttribute->attribute('measurement', $rawListItem['entity']);
            $identifier = str_replace('||identifier||', ' ' . $measurement['identifier'], $measurement['translation']);
            $totalDiameter += (float)$measurement['diameter'];
            $totalWeight += (float)$measurement['weight'];
            $transformed[] = [
                'identifier' => $identifier,
                'diameter' => $measurement['diameter'],
                'weight' => $measurement['weight']
            ];
        }
        $averageDiameter = $totalDiameter / count($rawListItems);
        $averageWeight = $totalWeight / count($rawListItems);
        $transformed[] = [
            'identifier' => $average['translation'],
            'diameter' => number_format($averageDiameter, 2),
            'weight' => number_format($averageWeight, 2)
        ];
        return $transformed;
    }
}