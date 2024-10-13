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
        $span = $this->elementFactory->span([], $entity['citation']);
        $listItemNodes[] = $this->elementFactory->listItem([$span]);
        foreach ($entity['list']['items'] as $listItem) {
            $type = $listItem['entity_type'] ?? $listItem['component_type'];
            $listItemNodes[] = match($type) {
                'identified_image_group' => $this->imageGroup($listItem),
                'upload_group' => $this->uploadGroup($listItem),
                'table' => $this->table($listItem),
                default => $this->paragraphItems($listItem)
            };
        }
        $componentNodes[] = $this->elementFactory->article($listItemNodes);
        return $componentNodes;
    }

    private function anchoredNewlineSentenceNodes(string $rawText): array
    {
        $nodes = [];
        $pattern = '/([^\[]*)(\[)([0-9]*)(\])([^\]]*)/';
        if (preg_match($pattern, $rawText, $matches)) {
            $nodes[] = $this->elementFactory->span(['class' => 'tmh_sentence'], $matches[1]);
            $nodes[] = $this->createAnchorRoute($matches[3]);
            $nodes[] = $this->elementFactory->span(['class' => 'tmh_sentence'], $matches[5]);
        }
        return $nodes;
    }

    private function articleUrl(): string
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL'];
    }

    private function createAnchorRoute(string $text): array
    {
        $attributes = ['href' => $this->articleUrl() . '#ref_' . $text, 'title' => $text];
        return $this->elementFactory->listItemLink($attributes, '&nbsp;[' . $text . ']&nbsp;');
    }

    private function createNamedRoute(string $text): array
    {
        $name = 'ref_' . $text;
        $attributes = ['href' => $this->articleUrl() . '#' . $name, 'name' => $name, 'title' => $text];
        return $this->elementFactory->listItemLink($attributes, '[' . $text . ']&nbsp;');
    }

    private function imageGroup(array $listItem): array
    {
        $imageGroupNodes = [];
        foreach ($listItem['images'] as $image) {
            $attributes = [
                'href' => $image['route']['href'] ?? '',
                'name' => $image['name'],
                'src' => $image['src'],
                'target' => '_self',
                'title' => $image['route']['title'] ?? ''
            ];
            $imageGroupNodes[] = $this->elementFactory->linkedImage($attributes);
        }
        $imageGroupNodes[] = $this->elementFactory->br();
        return $this->elementFactory->imageGroup($imageGroupNodes);
    }

    private function paragraphItems(array $listItems): array
    {
        $withNewlines = ['anchored_newline_sentence', 'bold_newline_sentence', 'newline_sentence'];
        $childNodes = [];
        foreach ($listItems['items'] as $listItem) {
            $childNodes =  array_merge($childNodes, match($listItem['type']) {
                'anchor_route' => [$this->createAnchorRoute($listItem['text'])],
                'anchored_newline_sentence' => $this->anchoredNewlineSentenceNodes($listItem['text']),
                'bold_newline_sentence',
                'bold_sentence' => [
                    $this->elementFactory->span(['class' => 'tmh_bold_sentence'], $listItem['text'])
                ],
                'italic_sentence' => [
                    $this->elementFactory->span(['class' => 'tmh_italic_sentence'], $listItem['text'])
                ],
                'named_route' => [$this->createNamedRoute($listItem['text'])],
                'underline_sentence' => [
                    $this->elementFactory->span(['class' => 'tmh_underline_sentence'], $listItem['text'])
                ],
                default => [
                    $this->elementFactory->span(['class' => 'tmh_sentence'], $listItem['text'])
                ]
            });
            if (in_array($listItem['type'], $withNewlines)) {
                $childNodes[] = $this->elementFactory->br();
            }
        }
        return $this->elementFactory->p($childNodes);
    }

    private function table(array $listItem): array
    {
        $rows = [];
        foreach ($listItem['list']['rows'] as $rowCells) {
            $cells = [];
            foreach ($rowCells as $rowCell) {
                $cells[] = $this->elementFactory->td(
                    ['colspan' => $rowCell['colspan'], 'class' => 'tmh_table_cell'],
                    $rowCell['text']
                );
            }
            $rows[] = $this->elementFactory->tr('tmh_table_row', $cells);
        }
        return $this->elementFactory->table('tmh_table', $rows);
    }

    private function uploadGroup(array $listItem): array
    {
        $childNodes = [];
        foreach ($listItem['upload_group']['uploads'] as $upload) {
            $attributes = [
                'href' => str_replace('/128/', '/1024/', $upload['src']),
                'name' => $upload['name'],
                'src' => $upload['src'],
                'target' => '_self',
                'title' => $upload['alt']
            ];
            $childNodes[] = $this->elementFactory->linkedImage($attributes);
        }
        return $this->elementFactory->uploadGroup($childNodes);
    }
}
