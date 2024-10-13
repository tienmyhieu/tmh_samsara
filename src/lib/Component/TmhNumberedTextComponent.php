<?php

namespace lib\Component;

class TmhNumberedTextComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        return [
            'class' => '',
            'entity_type' => $entity['type'],
            'translation' => $entity['identifier'] . '. ' . $entity['translation']
        ];
    }
}