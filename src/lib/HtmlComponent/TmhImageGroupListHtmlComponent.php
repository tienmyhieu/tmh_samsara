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
        $imageGroupNodes = [$this->elementFactory->span('', $listItem['translation']), $br];
        foreach ($listItem['images'] as $image) {
            $imageGroupNodes[] = $this->elementFactory->linkedImage(
                $image['route']['href'],
                $image['src'],
                $image['route']['title']
            );
        }
        $imageGroupNodes[] = $br;
        return $this->elementFactory->imageGroup($imageGroupNodes);
    }
}
