<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhImageGroupListHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $componentNodes = [];
        if (0 < strlen($entity['translation'])) {
            $componentNodes[] = $this->elementFactory->listTitle($entity['translation']);
        }
        foreach ($entity['list']['items'] as $listItem) {
            $componentNodes[] = $this->elementFactory->listItem([$this->transformListItem($listItem)]);
        }
        return [$this->elementFactory->imageGroupList($componentNodes)];
    }

    private function transformListItem(array $listItem): array
    {
        $br = $this->elementFactory->br();
        $thinBr = $this->elementFactory->thinBr();
        $imageGroupNodes = [];
        if (0 < strlen($listItem['text_above'])) {
            $imageGroupNodes[] = $this->elementFactory->span([], $listItem['text_above']);
            $imageGroupNodes[] = $br;
        }
        foreach ($listItem['images'] as $image) {
            if (empty($image['route'])) {
                $imageGroupNodes[] = $this->elementFactory->img('', $image['src']);
            } else {
                $attributes = [
                    'href' => $image['route']['href'],
                    'name' => $image['name'],
                    'src' => $image['src'],
                    'target' => '_self',
                    'title' => $image['route']['title']
                ];
                $imageGroupNodes[] = $this->elementFactory->linkedImage($attributes);
            }
        }
        $imageGroupNodes[] = $br;
        if (0 < count($listItem['text_below'])) {
            foreach ($listItem['text_below'] as $textBelow) {
                $imageGroupNodes[] = $this->elementFactory->span(['class' => 'tmh_smaller_text'], $textBelow);
                $imageGroupNodes[] = $thinBr;
            }
        }
        //$imageGroupNodes[] = $br;
        return $this->elementFactory->imageGroup($imageGroupNodes);
    }
}
