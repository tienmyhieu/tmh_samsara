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
        $uploads = [];
        foreach ($rawUploadGroup['images'] as $upload) {
            $uploads[] = [
                'src' => 'http://img1.tienmyhieu.com/uploads/128/' . $upload . '.jpg',
                'title' => $entity['translation']
            ];
        }
        return [
            'component_type' => $entity['type'],
            'upload_group' => ['translation' => $rawUploadGroup['translation'], 'uploads' => $uploads]
        ];
    }
}
