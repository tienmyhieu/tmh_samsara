<?php

namespace lib\Attribute;

class TmhPaleTextAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        return [
            'class' => '',
            'entity_type' => $entity['type'],
            'translation' => $entity['translation']
        ];
    }
}