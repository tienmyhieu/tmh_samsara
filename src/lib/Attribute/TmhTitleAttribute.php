<?php

namespace lib\Attribute;

class TmhTitleAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        $title = str_replace('||identifier||', ' ' . $entity['identifier'], $entity['translation']);
        return [
            'component_type' => $entity['type'],
            'translation' => $title
        ];
    }
}