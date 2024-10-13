<?php

namespace lib\Attribute;

class TmhDefaultTitleAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        return [
            'component_type' => $entity['type'],
            'translation' => $entity['documentTitle']
        ];
    }
}