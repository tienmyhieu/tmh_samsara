<?php

namespace lib\HtmlComponent;

use lib\TmhElementFactory;

class TmhTopicHtmlComponent implements TmhHtmlComponent
{
    private TmhElementFactory $elementFactory;

    public function __construct(TmhElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        return [$this->elementFactory->topic($entity['translation'])];
    }
}
