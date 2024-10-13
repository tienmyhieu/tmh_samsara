<?php

namespace lib\Component;

class TmhTitleComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        return [
            'component_type' => $entity['type'],
            'translation' => $entity['translation']
        ];
    }
}