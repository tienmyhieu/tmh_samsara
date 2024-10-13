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
        $rawParagraphItems = $this->entityAttribute->items('paragraph', $entity['entity']);
        return [
            'entity_type' => $entity['type'],
            'items' => $this->transformParagraphItems($rawParagraphItems)
        ];
    }

    private function transformParagraphItems(array $rawParagraphItems): array
    {
        $paragraphItems = [];
        foreach ($rawParagraphItems as $rawParagraphItem) {
            $paragraphItems[] = ['type'=> $rawParagraphItem['type'], 'text' => $rawParagraphItem['text']];
        }
        return $paragraphItems;
    }
}
