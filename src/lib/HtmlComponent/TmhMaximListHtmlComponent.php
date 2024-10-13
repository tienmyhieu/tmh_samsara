<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhMaximListHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $componentNodes = [];
        $listItemNodes = [];
        foreach ($entity['list']['items'] as $listItem) {
            $span = $this->elementFactory->span([], $listItem['translation']);
            $maximNodes = [$span, $this->elementFactory->br()];
            foreach ($listItem['citations'] as $citation) {
                $maximNodes[] = $this->elementFactory->indentedSmallText($citation);
                $maximNodes[] = $this->elementFactory->br();
            }
            $listItemNodes[] = $this->elementFactory->listItem($maximNodes);
        }
        $componentNodes[] = $this->elementFactory->maxims($listItemNodes);
        $componentNodes[] = $this->elementFactory->br();
        return $componentNodes;
    }
}
