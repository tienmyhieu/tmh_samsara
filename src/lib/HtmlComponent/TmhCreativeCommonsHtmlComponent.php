<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhCreativeCommonsHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }
    public function get(array $entity): array
    {
        $cc = $entity['creative_commons']['cc'];
        $icons = $entity['creative_commons']['svg'];
        $childNodes = [
            $this->elementFactory->span([], $cc['link_prefix']),
            $this->elementFactory->creativeCommonsLink($cc['href'], $cc['inner_html'], $cc['title'])
        ];
        foreach ($icons as $icon) {
            $childNodes[] = $this->elementFactory->svgImg($icon);
        }
        return [$this->elementFactory->creativeCommons($childNodes)];
    }
}