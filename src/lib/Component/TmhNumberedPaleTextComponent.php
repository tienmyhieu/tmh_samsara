<?php

namespace lib\Component;

class TmhNumberedPaleTextComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        return [
            'class' => 'tmh_pale_text',
            'entity_type' => $entity['type'],
            'translation' => $entity['identifier'] . '. ' . $entity['translation']
        ];
    }
}