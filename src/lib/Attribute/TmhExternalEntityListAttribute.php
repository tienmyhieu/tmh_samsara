<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;
use lib\Core\TmhEntityAttribute;

class TmhExternalEntityListAttribute implements TmhAttribute
{
    private TmhDatabase $database;
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhDatabase $database, TmhEntityAttribute $entityAttribute)
    {
        $this->database = $database;
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('external_entity_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('external_entity', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'translation' => $list['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        $transformed = [];
        foreach ($rawListItems as $rawListItem) {
            $webSiteUrl = $this->database->entity('website_url', $rawListItem['entity']);
            $webSite = $this->database->entity('website', $webSiteUrl['website']);
            $svgFile = $webSiteUrl['type'] == 'pdf' ? 'pdf' : 'external-link';
            $identifier = $webSiteUrl['type'] == 'identified' ? ', ' . $webSiteUrl['identifier'] : '';
            $transformed[] = [
                'entity_type' => $rawListItem['type'],
                'href' => $webSite['url'] . $webSiteUrl['url'],
                'innerHtml' => $webSiteUrl['translation'] . $identifier,
                'title' => $webSiteUrl['translation'],
                'svg' => 'http://img1.tienmyhieu.com/' . $svgFile . '.svg',
            ];
        }
        return $transformed;
    }
}