<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;

class TmhVideoGroupAttribute implements TmhAttribute
{
    private TmhDatabase $database;

    public function __construct(TmhDatabase $database)
    {
        $this->database = $database;
    }

    public function create(array $entity): array
    {
        $rawVideoGroup = $this->database->entity('video_group', $entity['entity']);
        $videos = [];
        foreach ($rawVideoGroup['videos'] as $rawVideo) {
            $video = $this->database->entity('video', $rawVideo);
            $timeStart = 0 < strlen($video['time_start']) ? '#t=' . $video['time_start'] : '';
            $videos[] = [
                'src' => 'http://img1.tienmyhieu.com/videos/' . $video['src'] . '.mp4' . $timeStart,
                'height' => $video['height'],
                'translation' => $video['translation'],
                'width' => $video['width']
            ];
        }
        return [
            'component_type' => $entity['type'],
            'video_group' => ['translation' => '', 'videos' => $videos]
        ];
    }
}