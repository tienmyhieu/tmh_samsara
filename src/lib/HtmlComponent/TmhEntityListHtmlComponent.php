<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhEntityListHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $componentNodes = [];
        if (0 < strlen($entity['translation'])) {
             $componentNodes[] = $this->elementFactory->listTitle($entity['translation']);
             $componentNodes[] = $this->elementFactory->br();
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
            'pale_numbered_shadow_route',
            'pale_shadow_route' => $this->elementFactory->paleListItemLink(
                $listItem['href'],
                $listItem['innerHtml'],
                $listItem['title']
            ),
            'numbered_shadow_route',
            'shadow_route',
            'route' => $this->elementFactory->listItemLink(
                [
                    'href' => $listItem['href'],
                    'title' => $listItem['title']
                ],
                $listItem['innerHtml']
            ),
            default => $this->textListItem($listItem)
        };
    }

    private function keyValueListListItem(array $listItem): array
    {
        return $this->elementFactory->indentedFartherSmallText($listItem['translation']);
    }

    private function textListItem(array $listItem): array
    {
        return $this->elementFactory->span(['class' => $listItem['class']], $listItem['translation']);
    }
}
