<?php

namespace lib\HtmlComponent;

use lib\TmhElementFactory;

class TmhVerticalQuoteListHtmlComponent implements TmhHtmlComponent
{
    private TmhElementFactory $elementFactory;

    public function __construct(TmhElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $quoteListNodes = [$this->elementFactory->span('', $entity['translation'])];
        $quoteItems = [];
        foreach ($entity['list']['items'] as $rawQuoteItem) {
            $span = $this->elementFactory->span('', $rawQuoteItem['quote']);
            $quoteItems[] = $this->elementFactory->quoteListItem([$span]);
        }
        $quoteListNodes[] = $this->elementFactory->quoteListVertical($quoteItems);
        return [$this->elementFactory->quoteList($quoteListNodes)];
    }
}
