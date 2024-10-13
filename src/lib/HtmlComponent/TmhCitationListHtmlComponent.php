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
        $br = $this->elementFactory->br();
        foreach ($entity['list']['items'] as $listItem) {
            $listItemNodes[] = $this->elementFactory->span('tmh_list_item', $listItem);
            $listItemNodes[] = $br;
        }
        $componentNodes[] = $this->elementFactory->citations($listItemNodes);
        $componentNodes[] = $br;
        return $componentNodes;
    }
}
