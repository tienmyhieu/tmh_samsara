<?php

namespace lib\Component;

use lib\TmhStructure;

class TmhCitationListComponent implements TmhComponent
{
    private TmhStructure $structure;

    public function __construct(TmhStructure $structure)
    {
        $this->structure= $structure;
    }

    public function get(array $entity): array
    {
        $list = $this->structure->entity('citation_list', $entity['targetUuid']);
        $rawListItems = $this->structure->listItems('citation', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        $transformed = [];
        $citations = $this->structure->entities('citation');
        foreach ($rawListItems as $rawListItem) {
            $citation = $citations[$rawListItem['citation']];
            $translation = str_replace('||page||', $rawListItem['page'], $citation['translation']);
            $transformed[] = str_replace('||plate||', $rawListItem['plate'], $translation);
        }
        return $transformed;
    }
}
