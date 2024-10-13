<?php

namespace lib\Component;

class TmhQuoteComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        return [
            'entity_type' => $entity['type'],
            'quote' => $entity['value']
        ];
    }
}