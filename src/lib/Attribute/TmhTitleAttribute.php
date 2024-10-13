<?php

namespace lib\Attribute;

class TmhTitleAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        return [
            'component_type' => $entity['type'],
            'translation' => $entity['translation']
        ];
    }
}