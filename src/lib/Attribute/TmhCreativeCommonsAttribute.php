<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhCreativeCommonsAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $creativeCommons = [];
        foreach ($this->entityAttribute->attributes('creative_commons') as $rawCreativeCommon) {
            $creativeCommons[$rawCreativeCommon['name']] = $rawCreativeCommon['translation'];
        }
        return [
            'component_type' => $entity['type'],
            'creative_commons' => [
                'svg' => [
                    'http://img1.tienmyhieu.com/cc.svg',
                    'http://img1.tienmyhieu.com/by.svg',
                    'http://img1.tienmyhieu.com/nc.svg',
                    'http://img1.tienmyhieu.com/sa.svg'
                ],
                'cc' => $creativeCommons
            ]
        ];
    }
}