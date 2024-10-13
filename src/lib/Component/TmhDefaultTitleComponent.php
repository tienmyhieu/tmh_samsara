<?php

namespace lib\Component;

class TmhDefaultTitleComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        return [
            'component_type' => $entity['type'],
            'translation' => $entity['documentTitle']
        ];
    }
}