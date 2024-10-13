<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhHorizontalQuoteListHtmlComponent implements TmhHtmlComponent
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
            $span = $this->elementFactory->span([], $rawQuoteItem['page'] . ': ' . $rawQuoteItem['quote']);
            $quoteItems[] = $this->elementFactory->listItem([$span]);
        }
        $quoteItems[] = $this->elementFactory->br();
        $quoteListNodes[] = $this->elementFactory->quoteListHorizontal($quoteItems);
        return [$this->elementFactory->quoteList($quoteListNodes)];
    }
}