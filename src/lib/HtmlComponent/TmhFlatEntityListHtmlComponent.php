<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhFlatEntityListHtmlComponent implements TmhHtmlComponent
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
        }
        $componentNodes = [];
        $i = 0;
        foreach ($entity['list']['items'] as $listItem) {
            $componentNodes[] = $this->transformListItem($listItem);
            if ($i == 0) {
                $componentNodes[] = $this->elementFactory->br();
            } else if ($i < count($entity['list']['items']) - 1) {
                $componentNodes[] = $this->elementFactory->span([], ', ');
            }
            $i++;
        }
        if (0 < count($componentNodes)) {
            $componentNodes[] = $this->elementFactory->br();
            $componentNodes[] = $this->elementFactory->br();
        }
        return [$this->elementFactory->entityList($componentNodes)];
    }

    private function transformListItem(array $listItem): array
    {
        return match($listItem['entity_type']) {
            'shadow_route',
            'route' => $this->elementFactory->listItemLink(
                ['href' => $listItem['href'], 'title' => $listItem['title']],
                $listItem['innerHtml']
            ),
            default => $this->textListItem($listItem)
        };
    }

    private function textListItem(array $listItem): array
    {
        return $this->elementFactory->span(['class' => $listItem['class']], $listItem['translation']);
    }
}