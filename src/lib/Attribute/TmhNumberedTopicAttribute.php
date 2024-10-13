<?php

namespace lib\Attribute;

class TmhNumberedTopicAttribute implements TmhAttribute
{
    public function create(array $entity): array
    {
        $hasIdentifier = str_contains($entity['translation'], '||identifier||');
        $identifiedNumberedTopic = str_replace('||identifier||', ' ' . $entity['identifier'], $entity['translation']);
        $numberedTopic = $entity['translation'] . ' ' . $entity['identifier'];
        return [
            'component_type' => $entity['type'],
            'translation' => $hasIdentifier ? $identifiedNumberedTopic : $numberedTopic
        ];
    }
}