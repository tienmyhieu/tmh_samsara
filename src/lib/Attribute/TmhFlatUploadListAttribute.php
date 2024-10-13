<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;
use lib\Core\TmhEntityAttribute;

class TmhFlatUploadListAttribute implements TmhAttribute
{
    private TmhDatabase $database;
    private TmhEntityAttribute $entityAttribute;

    public function __construct(TmhDatabase $database, TmhEntityAttribute $entityAttribute)
    {
        $this->database = $database;
        $this->entityAttribute = $entityAttribute;
    }

    public function create(array $entity): array
    {
        $list = $this->entityAttribute->attribute('flat_upload_list', $entity['targetUuid']);
        $rawListItems = $this->entityAttribute->listItems('flat_upload', $entity['targetUuid']);
        $translation = str_replace('||identifier||', '', $list['translation']);
        $translation .= ' ' . $list['identifier'];
        $hasTitle = 0 < strlen($list['translation']);
        return [
            'component_type' => $entity['type'],
            'translation' =>$hasTitle ? $translation : '',
            'list' => ['items' => $this->transformListItems($rawListItems)]
        ];
    }

    private function transformListItems(array $rawListItems): array
    {
        $transformed = [];
        foreach ($rawListItems as $rawListItem) {
            $upload = $this->database->entity('upload', $rawListItem['entity']);
            $hasHref = 0 < strlen($upload['href']);
            $href = $hasHref ? $upload['href'] : $upload['src'];
            $transformed[] = [
                'href' => 'http://img1.tienmyhieu.com/uploads/1024/' . $href . '.jpg',
                'innerHtml' => $rawListItem['translation'],
                'title' => $upload['alt']
            ];
        }
        return $transformed;
    }
}