<?php

namespace lib\Component;

use lib\TmhDatabase;

class TmhUploadGroupComponent implements TmhComponent
{
    private TmhDatabase $database;

    public function __construct(TmhDatabase $database)
    {
        $this->database = $database;
    }
    public function get(array $entity): array
    {
        $rawUploadGroup = $this->database->entity('upload_group', $entity['entity']);
        $translation = $rawUploadGroup['translation'];
        $imageTitle = $entity['translation'];
        if (0 < strlen($entity['identifier']) && '1' == $entity['identifier']) {
            $imageTitle = $entity['documentTitle'];
        }
        $uploads = [];
        foreach ($rawUploadGroup['images'] as $upload) {
            $uploads[] = [
                'src' => 'http://img1.tienmyhieu.com/uploads/128/' . $upload . '.jpg',
                'title' => $imageTitle
            ];
        }
        return [
            'component_type' => $entity['type'],
            'upload_group' => ['translation' => $translation, 'uploads' => $uploads]
        ];
    }
}
