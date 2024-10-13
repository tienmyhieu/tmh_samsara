<?php

namespace lib\HtmlComponent;

use lib\TmhElementFactory;

class TmhCitationListHtmlComponent implements TmhHtmlComponent
{
    private TmhElementFactory $elementFactory;

    public function __construct(TmhElementFactory $elementFactory)
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
