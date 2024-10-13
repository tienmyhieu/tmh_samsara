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
            $componentNodes[] = $this->elementFactory->span('', $entity['translation']);
        }
        foreach ($entity['list']['items'] as $listEntity ) {
            $entityChildNodes = [];
            foreach ($listEntity['upload_group']['uploads'] as $upload) {
                $entityChildNodes[] = $this->elementFactory->linkedImage(
                    str_replace('/128/', '/1024/', $upload['src']),
                    $upload['src'],
                    $upload['title']
                );
            }
            $entityChildNodes[] = $this->elementFactory->listItem([$this->elementFactory->smallText('&nbsp;')]);
            $componentNodes[] = $this->elementFactory->uploadGroup($entityChildNodes);
        }
        return [$this->elementFactory->uploadGroupList($componentNodes)];
    }
}
