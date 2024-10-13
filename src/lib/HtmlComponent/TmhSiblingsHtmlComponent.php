<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhSiblingsHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $childNodes = [];
        $i = 0;
        foreach ($entity['siblings'] as $entitySibling) {
            $siblingNodes = [];
            $siblingNodes[] = $this->elementFactory->siblingItemLink(
                $entitySibling['href'],
                $entitySibling['innerHtml'],
                $entitySibling['title']
            );
            if ($i < count($entity['siblings']) - 1) {
                $siblingNodes[] = $this->elementFactory->span([], '&nbsp;&#9675;&nbsp;');
            }
            $childNodes[] = $this->elementFactory->siblingItem($siblingNodes);
            $i++;
        }
        return [$this->elementFactory->siblings($childNodes)];
    }
}
