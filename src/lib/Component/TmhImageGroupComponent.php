<?php

namespace lib\Component;

use lib\TmhDatabase;
use lib\TmhRoute;
use lib\TmhStructure;

class TmhImageGroupComponent implements TmhComponent
{
    private TmhDatabase $database;
    private TmhStructure $structure;

    public function __construct(TmhDatabase $database, TmhStructure $structure)
    {
        $this->database = $database;
        $this->structure = $structure;
    }

    public function get(array $entity): array
    {
        $imageGroup = $this->database->entity('image_group', $entity['entity']);
        $imageGroup['translation'] = $this->imageGroupTitle($imageGroup);
        $imageGroup['entity_type'] = $entity['type'];
        $imageGroup['images'] = $this->imageGroupImages($imageGroup['images'], $imageGroup['route']);
        return $this->pruneEntity($imageGroup);
    }

    private function imageGroupImages(array $imageGroupImages, string $route): array
    {
        $images = [];
        foreach ($imageGroupImages as $imageGroupImage) {
            $rawImage = $this->database->entity('image', $imageGroupImage);
            $route = [
                'href' => 'http://img1.tienmyhieu.com/images/1024/' . $rawImage['src'] . '.jpg',
                'innerHtml' => '',
                'title' => $rawImage['alt']
            ];
            $image = [
                'alt' => $rawImage['alt'],
                'src' => 'http://img1.tienmyhieu.com/images/128/' . $rawImage['src'] . '.jpg',
                'route' => $route
            ];
            $images[] = $image;
        }
        return $images;
    }

    private function imageGroupTitle(array $imageGroup): string
    {
        $after = '';
        $before = '';
        $middle = '';
        if (0 < strlen($imageGroup['translation'])) {
            $middle = str_replace('||identifier||', '', $imageGroup['translation']);
            $middle .= ' ' . $imageGroup['identifier'];
        }
        if (0 < strlen($imageGroup['text_group'])) {
            $textGroup = $this->structure->entity('text_group', $imageGroup['text_group']);
            $after = $this->transformTextGroupItems($textGroup['after']);
            if (0 < strlen($after) && 0 < strlen(trim($middle))) {
                $after = ' - ' . $after;
            }
            $before = $this->transformTextGroupItems($textGroup['before']);
            if (0 < strlen($before) && 0 < strlen(trim($middle))) {
                $before .= ' - ';
            }
        }

        return $before . $middle . $after;
    }

    private function pruneEntity(array $entity): array
    {
        $keys = ['active', 'entity', 'identifier', 'type', 'uuid'];
        foreach ($keys as $key) {
            if(array_key_exists($key, $entity)) {
                unset($entity[$key]);
            }
        }
        return $entity;
    }

    private function transformTextGroupItems(array $dynamicListItems): string
    {
        $textGroupItems = '';
        foreach ($dynamicListItems as $dynamicKey => $dynamicValue) {
            $keyValueKey = $this->structure->entity('key_value_key', $dynamicKey);
            $textGroupItems .= $keyValueKey['translation'] . ': ' . $dynamicValue;
        }
        return $textGroupItems;
    }
}
