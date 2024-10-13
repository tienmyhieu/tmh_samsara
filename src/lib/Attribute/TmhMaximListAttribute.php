<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhMaximListAttribute implements TmhAttribute
{
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhEntityAttribute $entityAttribute)
    {
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('maxim_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->languageItems($entity['lang'], 'maxim_list', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        $transformed = [];
        $rawCitations = $this->entityAttribute->attributes('citation');
        $plate = $this->entityAttribute->attribute('key_value_key', '44w4hrim');
        $plateCitation = ', ' . $plate['translation'] . ': ||plate||';
        foreach ($rawListItems as $uuid => $rawListItem) {
            $maximListItemCitations = $this->entityAttribute->maximListItemCitations($uuid);
            $citations = [];
            foreach ($maximListItemCitations as $maximListItemCitation) {
                $citationTranslation = '';
                if (in_array($maximListItemCitation['citation'], array_keys($rawCitations))) {
                    $citation = $rawCitations[$maximListItemCitation['citation']];
                    $citationTranslation = $citation['translation'];
                }
                $translation = str_replace('||page||', $maximListItemCitation['page'], $citationTranslation);
                $translation = str_replace($plateCitation, '', $translation);
                if (0 < strlen($translation)) {
                    $citations[] = $translation;
                }
            }
            $transformed[] = ['translation' => $rawListItem['translation'], 'citations' => $citations];
        }
        return $transformed;
    }
}
