<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhVideoGroupListHtmlComponent implements TmhHtmlComponent
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
            $componentNodes[] = $this->elementFactory->span([], $entity['translation']);
        }
        foreach ($entity['list']['items'] as $listEntity ) {
            $entityChildNodes = [];
            foreach ($listEntity['video_group']['videos'] as $video) {
                if (0 < strlen($video['translation'])) {
                    $entityChildNodes[] = $this->elementFactory->span([], $video['translation']);
                }
                $entityChildNodes[] = $this->elementFactory->video(
                    $video['height'],
                    $video['src'],
                    $video['width']
                );
            }
            $componentNodes[] = $this->elementFactory->videoGroup($entityChildNodes);
        }
        return [$this->elementFactory->videoGroupList($componentNodes)];
    }
}