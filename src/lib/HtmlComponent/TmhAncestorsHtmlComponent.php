<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhAncestorsHtmlComponent implements TmhHtmlComponent
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
        foreach ($entity['ancestors'] as $entityAncestor) {
            $ancestorNodes = [];
            if ($i < count($entity['ancestors']) - 1) {
                $ancestorNodes[] = $this->elementFactory->ancestorItemLink(
                    $entityAncestor['href'],
                    $entityAncestor['innerHtml'],
                    $entityAncestor['title']
                );
                $ancestorNodes[] = $this->elementFactory->span([], '&nbsp;&raquo;&nbsp;');
            } else {
                $ancestorNodes[] = $this->elementFactory->span([], str_replace('_', ' ', $entityAncestor['innerHtml']));
            }
            $childNodes[] = $this->elementFactory->ancestorItem($ancestorNodes);
            $i++;
        }
        return [$this->elementFactory->ancestors($childNodes)];
    }
}
