<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhUploadGroupHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
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
