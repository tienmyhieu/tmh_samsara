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
            $this->elementFactory->span([], $entity['upload_group']['translation']),
            $this->elementFactory->br()
        ];
        foreach ($entity['upload_group']['uploads'] as $upload) {
            $attributes = [
                'href' => str_replace('/128/', '/1024/', $upload),
                'name' => $upload['name'],
                'src' => $upload,
                'title' => $upload['title']
            ];
            $childNodes[] = $this->elementFactory->linkedImage($attributes);
        }
        return [$this->elementFactory->uploadGroup($childNodes)];
    }
}
