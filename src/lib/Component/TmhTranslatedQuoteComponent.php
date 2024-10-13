<?php

namespace lib\Component;

class TmhTranslatedQuoteComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        return [
            'entity_type' => $entity['type'],
            'quote' => $entity['translation']
        ];
    }
}
