<?php

namespace lib\HtmlComponent;

use lib\TmhElementFactory;

class TmhEntityListHtmlComponent implements TmhHtmlComponent
{
    private TmhElementFactory $elementFactory;

    public function __construct(TmhElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $componentNodes = [];
        if (0 < strlen($entity['translation'])) {
             $componentNodes[] = $this->elementFactory->listTitle($entity['translation']);
        }
        foreach ($entity['list']['items'] as $listItem) {
            $componentNodes[] = $this->elementFactory->listItem([$this->transformListItem($listItem)]);
        }
        $componentNodes[] = $this->elementFactory->br();
        return [$this->elementFactory->entityList($componentNodes)];
    }

    private function transformListItem(array $listItem): array
    {
        return match($listItem['entity_type']) {
            'text_group' => $this->keyValueListListItem($listItem),
            'numbered_shadow_route',
            'shadow_route',
            'route' => $this->elementFactory->listItemLink(
                $listItem['href'],
                $listItem['innerHtml'],
                $listItem['title']
            ),
            default => $this->textListItem($listItem)
        };
    }

    private function keyValueListListItem(array $listItem): array
    {
        return $this->elementFactory->smallText($listItem['translation']);
    }

    private function textListItem(array $listItem): array
    {
        return $this->elementFactory->span($listItem['class'], $listItem['translation']);
    }
}
