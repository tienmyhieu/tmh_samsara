<?php

namespace lib\Attribute;

class TmhTopicAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        $translation = str_replace('||identifier||', ' ' . $entity['identifier'], $entity['translation']);
        return [
            'component_type' => $entity['type'],
            'translation' => $translation
        ];
    }
}
