<?php

namespace lib\Attribute;

class TmhNumberedTopicAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        return [
            'component_type' => $entity['type'],
            'translation' => $entity['translation'] .= ' ' . $entity['identifier']
        ];
    }
}