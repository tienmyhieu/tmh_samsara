<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhUploadGroupListHtmlComponent implements TmhHtmlComponent
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
            foreach ($listEntity['upload_group']['uploads'] as $upload) {
                $attributes = [
                    'href' => $upload['href'],
                    'name' => $upload['name'],
                    'src' => $upload['src'],
                    'target' => $this->isExternal($upload) ? '_blank': '_self',
                    'title' => $upload['title']
                ];
                $entityChildNodes[] = $this->elementFactory->linkedImage($attributes);
            }
            $entityChildNodes[] = $this->elementFactory->listItem([$this->elementFactory->smallText('&nbsp;')]);
            $componentNodes[] = $this->elementFactory->uploadGroup($entityChildNodes);
        }
        return [$this->elementFactory->uploadGroupList($componentNodes)];
    }

    private function uploadLink(array $upload): string
    {
        return match ($upload['type']) {
            'external' => $upload['href'],
            default => str_replace('/128/', '/1024/', $upload['src'])
        };
    }

    private function isExternal(array $upload): bool
    {
        return $upload['type'] == 'external';
    }
}
