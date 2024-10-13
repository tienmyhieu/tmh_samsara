<?php

namespace lib\Attribute;

class TmhNumberedTextAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        return [
            'class' => '',
            'entity_type' => $entity['type'],
            'translation' => $entity['identifier'] . '. ' . $entity['translation']
        ];
    }
}