<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;
use lib\Core\TmhRoute;

class TmhRoutedImageGroupAttribute implements TmhAttribute
{
    private TmhDatabase $database;
    private TmhRoute $route;

    public function __construct(TmhDatabase $database, TmhRoute $route)
    {
        $this->database = $database;
        $this->route = $route;
    }

    public function create(array $entity): array
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