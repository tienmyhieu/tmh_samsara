<?php

namespace lib\HtmlComponent;

use lib\TmhElementFactory;

class TmhQuoteListHtmlComponent implements TmhHtmlComponent
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
            $span = $this->elementFactory->span('', $rawQuoteItem);
            $quoteItems[] = $this->elementFactory->quoteListItem([$span]);
        }
        $quoteNode = $this->elementFactory->quoteListHorizontal($quoteItems);
        if ($entity['writing_mode'] == 'vertical-rl') {
            $quoteNode = $this->elementFactory->quoteListVertical($quoteItems);
        }
        $quoteListNodes[] = $quoteNode;
        return [$this->elementFactory->quoteList($quoteListNodes)];
    }
}
