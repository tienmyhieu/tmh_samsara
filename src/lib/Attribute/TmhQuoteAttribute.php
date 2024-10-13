<?php

namespace lib\Attribute;

class TmhQuoteAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        return [
            'entity_type' => $entity['type'],
            'quote' => $entity['value']
        ];
    }
}