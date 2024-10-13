<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;

class TmhUploadGroupAttribute implements TmhAttribute
{
    private TmhDatabase $database;

    public function __construct(TmhDatabase $database)
    {
        $this->database = $database;
    }

    public function create(array $entity): array
    {
        $rawUploadGroup = $this->database->entity('upload_group', $entity['entity']);
        $uploads = [];
        foreach ($rawUploadGroup['uploads'] as $rawUpload) {
            $upload = $this->database->entity('upload', $rawUpload);
            $uploads[] = [
                'alt' => $upload['alt'],
                'src' => 'http://img1.tienmyhieu.com/uploads/128/' . $upload['src'] . '.jpg',
                'title' => $upload['alt']
            ];
        }
        return [
            'component_type' => $entity['type'],
            'upload_group' => ['translation' => $rawUploadGroup['translation'], 'uploads' => $uploads]
        ];
    }
}
