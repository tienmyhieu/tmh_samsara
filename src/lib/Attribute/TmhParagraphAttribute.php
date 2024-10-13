<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhParagraphAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $rawSentences = $this->entityAttribute->items('paragraph', $entity['entity']);
        return [
            'entity_type' => $entity['type'],
            'sentences' => $this->sentences($rawSentences)
        ];
    }

    private function sentences(array $rawSentences): array
    {
        $sentences = [];
        foreach ($rawSentences as $rawSentence) {
            $sentences[] = $rawSentence['text'];
        }
        return $sentences;
    }
}
