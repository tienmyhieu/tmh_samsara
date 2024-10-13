<?php

namespace lib\Component;

use lib\TmhStructure;

class TmhCreativeCommonsComponent implements TmhComponent
{
    private TmhStructure $structure;

    public function __construct(TmhStructure $structure)
    {
        $this->structure = $structure;
    }

    public function get(array $entity): array
    {
        $creativeCommons = [];
        foreach ($this->structure->entities('creative_commons') as $rawCreativeCommon) {
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