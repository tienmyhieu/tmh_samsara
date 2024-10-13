<?php

namespace lib\Component;

class TmhTextComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        return [
            'class' => '',
            'entity_type' => $entity['type'],
            'translation' => $entity['translation']
        ];
    }
}