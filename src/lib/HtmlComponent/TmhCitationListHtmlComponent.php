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
            $attributes = ['class' => 'tmh_list_item'];
            if (isset($listItem['lang']) && 0 < strlen($listItem['lang'])) {
                $attributes['lang'] = $listItem['lang'];
            }
            $listItemNodes[] = $this->elementFactory->span($attributes, $listItem['citation']);
            $listItemNodes[] = $br;
        }
        $componentNodes[] = $this->elementFactory->citations($listItemNodes);
        $componentNodes[] = $br;
        return $componentNodes;
    }
}
