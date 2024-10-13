<?php

namespace lib\Component;

class TmhNumberedTopicComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        return [
            'component_type' => $entity['type'],
            'translation' => $entity['translation'] .= ' ' . $entity['identifier']
        ];
    }
}