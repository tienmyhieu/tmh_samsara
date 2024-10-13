<?php

namespace lib\HtmlComponent;

use lib\TmhElementFactory;

class TmhUploadGroupHtmlComponent implements TmhHtmlComponent
{
    private TmhElementFactory $elementFactory;

    public function __construct(TmhElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }
    public function get(array $entity): array
    {
        $childNodes = [
            $this->elementFactory->br(),
            $this->elementFactory->span('', $entity['upload_group']['translation']),
            $this->elementFactory->br()
        ];
        foreach ($entity['upload_group']['uploads'] as $upload) {
            $childNodes[] = $this->elementFactory->linkedImage(
                str_replace('/128/', '/1024/', $upload),
                $upload,
                $entity['upload_group']['translation']
            );
        }
        return [$this->elementFactory->uploadGroup($childNodes)];
    }
}
