<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhArticleHtmlComponent implements TmhHtmlComponent
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
        $span = $this->elementFactory->span('', $entity['citation']);
        $listItemNodes[] = $this->elementFactory->listItem([$span]);
        foreach ($entity['list']['items'] as $listItem) {
            $listItemNodes[] = match($listItem['entity_type']) {
                'identified_image_group' => $this->imageGroup($listItem),
                default => $this->paragraphItems($listItem)
            };
        }
        $componentNodes[] = $this->elementFactory->article($listItemNodes);
        return $componentNodes;
    }

    private function imageGroup(array $listItem): array
    {
        $imageGroupNodes = [];
        foreach ($listItem['images'] as $image) {
            $imageGroupNodes[] = $this->elementFactory->linkedImage(
                $image['route']['href'],
                $image['src'],
                $image['route']['title']
            );
        }
        $imageGroupNodes[] = $this->elementFactory->br();
        return $this->elementFactory->imageGroup($imageGroupNodes);
    }

    private function paragraphItems(array $listItems): array
    {
        $childNodes = [];
        foreach ($listItems['items'] as $listItem) {
            $childNodes[] =  match($listItem['type']) {
                'anchor_route' => $this->createAnchorRoute($listItem['text']),
                'bold_sentence' => $this->elementFactory->span('tmh_bold_sentence', $listItem['text']),
                'italic_sentence' => $this->elementFactory->span('tmh_italic_sentence', $listItem['text']),
                default => $this->elementFactory->span('tmh_sentence', $listItem['text'])
            };
            if ($listItem['type'] == 'newline_sentence') {
                $childNodes[] = $this->elementFactory->br();
            }
        }
        return $this->elementFactory->p($childNodes);
    }

    private function createAnchorRoute(string $text): array
    {
        return $this->elementFactory->listItemLink(
            $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL'] . '#' . $text,
            '&nbsp;[' . $text . ']&nbsp;',
            $text
        );
    }
}
