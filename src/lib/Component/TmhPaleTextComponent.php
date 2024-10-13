<?php

namespace lib\Component;

class TmhPaleTextComponent implements TmhComponent
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