<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhCitationListHtmlComponent implements TmhHtmlComponent
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
        if (0 < strlen($entity['translation'])) {
            $componentNodes[] = $this->elementFactory->topic($entity['translation']);
        }
        foreach ($entity['list']['items'] as $listItem) {
            $span = $this->elementFactory->span('', $listItem);
            $listItemNodes[] = $this->elementFactory->listItem([$span]);
        }
        $componentNodes[] = $this->elementFactory->citations($listItemNodes);
        $componentNodes[] = $this->elementFactory->br();
        return $componentNodes;
    }
}
