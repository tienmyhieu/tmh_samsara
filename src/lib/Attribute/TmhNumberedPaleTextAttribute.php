<?php

namespace lib\Attribute;

class TmhNumberedPaleTextAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        return [
            'class' => 'tmh_pale_text',
            'entity_type' => $entity['type'],
            'translation' => $entity['identifier'] . '. ' . $entity['translation']
        ];
    }
}