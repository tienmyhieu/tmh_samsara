<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhExternalEntityListHtmlComponent implements TmhHtmlComponent
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
            $svg = $this->elementFactory->svgImg($listItem['svg']);
            $span = $this->elementFactory->span([], $listItem['innerHtml']);
            $link = $this->elementFactory->externalListItemLink(
                [
                    'href' => $listItem['href'],
                    'title' => $listItem['title']
                ],
                '',
                [$span, $svg]
            );
            $componentNodes[] = $this->elementFactory->listItem([$link]);
        }
        $componentNodes[] = $this->elementFactory->br();
        return [$this->elementFactory->entityList($componentNodes)];
    }
}