<?php

namespace lib\Component;

class TmhTopicComponent implements TmhComponent
{
    public function get(array $entity): array
    {
        $translation = $entity['translation'];
        if (0 < strlen($entity['targetUuid'])) {
            $translation .= ' ' . $entity['targetUuid'];
        }
        return [
            'component_type' => $entity['type'],
            'translation' => $translation
        ];
    }
}
