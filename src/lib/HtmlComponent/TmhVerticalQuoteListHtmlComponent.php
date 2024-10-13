<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhVerticalQuoteListHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $quoteListNodes = [$this->elementFactory->span([], $entity['translation'])];
        $quoteItems = [];
        foreach ($entity['list']['items'] as $rawQuoteItem) {
            $span = $this->elementFactory->span([], $rawQuoteItem['quote']);
            $quoteItems[] = $this->elementFactory->verticalQuoteListItem([$span]);
        }
        $quoteListNodes[] = $this->elementFactory->quoteListVertical($quoteItems);
        return [$this->elementFactory->quoteList($quoteListNodes)];
    }
}
