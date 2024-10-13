<?php

namespace lib\Component;

use lib\TmhDatabase;
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
        $imageGroup['images'] = $this->imageGroupImages($imageGroup['images']);
        return $this->pruneEntity($imageGroup);
    }

    private function imageGroupImages(array $imageGroupImages): array
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
        $rawListItems = $this->structure->items('text_group', $imageGroup['text_group']);
        return implode(' - ', $this->transformTextGroupItems($rawListItems));
    }

    private function page(string $key, string $page): string
    {
        $keyValueKey = $this->structure->entity('key_value_key', $key);
        return $keyValueKey['translation'] . ': ' . $page;
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

    private function transformTextGroupItems(array $textGroupItems): array
    {
        $items = [];
        foreach ($textGroupItems as $textGroupItem) {
            $items[] = match($textGroupItem['type']) {
                'page' => $this->page($textGroupItem['key'], $textGroupItem['value']),
                'year' => $this->year($textGroupItem['key'], $textGroupItem['value']),
                default => $textGroupItem['translation']
            };
        }
        return $items;
    }

    private function year(string $key, string $year): string
    {
        $keyValueKey = $this->structure->entity('key_value_key', $key);
        return $keyValueKey['translation'] . ': ' . $year;
    }
}
