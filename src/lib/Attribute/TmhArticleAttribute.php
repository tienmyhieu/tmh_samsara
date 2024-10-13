<?php

namespace lib\Attribute;

use lib\Core\TmhEntityAttribute;

class TmhArticleAttribute implements TmhAttribute
{
    private TmhAttributeFactory $componentFactory;
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhAttributeFactory $componentFactory, TmhEntityAttribute $entityAttribute)
    {
        $this->componentFactory = $componentFactory;
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $rawArticle = $this->entityAttribute->attribute('article', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->languageItems($rawArticle['language'], 'article', $entity['targetUuid']);
        return [
            'component_type' => $entity['type'],
            'citation' => $this->citation($rawArticle),
            'translation' => $rawArticle['translation'],
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function citation(array $rawArticle): string
    {
        $author = 0 < strlen($rawArticle['translation']) ? $rawArticle['translation'] : $rawArticle['author'];
        $authorKey = $this->entityAttribute->attribute('key_value_key', 'l05of9ks');
        $dateKey = $this->entityAttribute->attribute('key_value_key', 'lxt8lwyr');
        $author = $authorKey['translation'] .': ' . $author;
        $date = $dateKey['translation'] . ': ' . $rawArticle['date'];
        return $author . ' - ' . $date;
    }

    private function transformListItems(array $rawListItems): array
    {
        return array_map(function($rawListItem) {
            $extraFields = ['entity' => $rawListItem['targetUuid']];
            $rawListItem = array_merge($rawListItem, $extraFields);
            return $this->componentFactory->create($rawListItem['type'])->create($rawListItem);
        }, $rawListItems);
    }
}
