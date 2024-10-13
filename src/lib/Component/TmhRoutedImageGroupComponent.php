<?php

namespace lib\Component;

use lib\TmhDatabase;
use lib\TmhRoute;
use lib\TmhStructure;

class TmhRoutedImageGroupComponent implements TmhComponent
{
    private TmhDatabase $database;
    private TmhRoute $route;
    private TmhStructure $structure;

    public function __construct(TmhDatabase $database, TmhRoute $route, TmhStructure $structure)
    {
        $this->database = $database;
        $this->route = $route;
        $this->structure = $structure;
    }

    public function get(array $entity): array
    {
        $imageGroup = $this->database->entity('image_group', $entity['entity']);
        $translation = str_replace('||identifier||', '', $imageGroup['translation']);
        $translation .= ' ' . $imageGroup['identifier'];
        return [
            'entity_type' => $entity['type'],
            'images' => $this->imageGroupImages($imageGroup),
            'translation' => $translation
        ];
    }

    private function imageGroupImages(array $imageGroup): array
    {
        $images = [];
        $route = $this->route->routeEntityByKey($imageGroup['route']);
        foreach ($imageGroup['images'] as $imageGroupImage) {
            $rawImage = $this->database->entity('image', $imageGroupImage);
            $images[] = [
            'alt' => $rawImage['alt'],
                'src' => 'http://img1.tienmyhieu.com/images/128/' . $rawImage['src'] . '.jpg',
                'route' => $route
            ];
        }
        return $images;
    }
}