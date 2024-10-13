<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhQuoteAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $page = $this->entityAttribute->attribute('key_value_key', '1sf7ayc8');
        return [
            'entity_type' => $entity['type'],
            'quote' => $entity['value'],
            'page' => $page['translation'] . ' ' . $entity['page']
        ];
    }
}