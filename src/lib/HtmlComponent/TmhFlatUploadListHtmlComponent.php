<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhFlatUploadListHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $componentNodes = [];
        $br = $this->elementFactory->br();
        if (0 < strlen($entity['translation'])) {
            $componentNodes[] = $this->elementFactory->span([], $entity['translation']);
            $componentNodes[] = $br;
        }

        $i = 0;
        foreach ($entity['list']['items'] as $listItem) {
            $componentNodes[] = $this->elementFactory->listItemLink(
                ['href' => $listItem['href'], 'title' => $listItem['title']],
                $listItem['innerHtml']
            );
            if ($i < count($entity['list']['items']) - 1) {
                $componentNodes[] = $this->elementFactory->span([], ', ');
            }
            $i++;
        }

        return [$this->elementFactory->entityList($componentNodes)];
    }
}