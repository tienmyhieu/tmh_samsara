<?php

namespace lib\Attribute;

class TmhTranslatedQuoteAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        return [
            'entity_type' => $entity['type'],
            'quote' => $entity['translation']
        ];
    }
}
