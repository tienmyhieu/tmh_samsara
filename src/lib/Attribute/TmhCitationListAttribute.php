<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhCitationListAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('citation_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('citation', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems, $entity['lang'])]
        ];
    }

    private function transformListItems(array $rawListItems, string $language): array
    {
        $transformed = [];
        $citations = $this->entityAttribute->attributes('citation');
        $plate = $this->entityAttribute->attribute('key_value_key', '44w4hrim');
        $plateCitation = ', ' . $plate['translation'] . ': ||plate||';
        $page = $this->entityAttribute->attribute('key_value_key', '1sf7ayc8');
        $pageCitation = ', ' . $page['translation'] . ': ||page||';
        foreach ($rawListItems as $rawListItem) {
            $citation = $citations[$rawListItem['citation']];
            $useLanguage = $citation['lang'] != $language;
            $transformedCitation = $citation['translation'];
            if (0 < strlen($rawListItem['page'])) {
                $transformedCitation = str_replace('||page||', $rawListItem['page'], $transformedCitation);
            } else {
                $transformedCitation = str_replace($pageCitation, '', $transformedCitation);
            }
            if (0 < strlen($rawListItem['plate'])) {
                $transformedCitation = str_replace('||plate||', $rawListItem['plate'], $transformedCitation);
            } else {
                $transformedCitation = str_replace($plateCitation, '', $transformedCitation);
            }
            $transformed[] = ['citation' => $transformedCitation, 'lang' => ($useLanguage ? $citation['lang'] : '')];
        }
        return $transformed;
    }
}
