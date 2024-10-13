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
                default => $this->paragraph($listItem)
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

    private function paragraph(array $listItem): array
    {
        $innerHtml = implode(' ', $listItem['sentences']);
        return $this->elementFactory->p($innerHtml);
    }
}
