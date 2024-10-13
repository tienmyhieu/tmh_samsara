<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;
use lib\Core\TmhEntityAttribute;
use lib\Core\TmhRoute;

class TmhRoutedImageGroupAttribute implements TmhAttribute
{
    private TmhDatabase $database;
    private TmhEntityAttribute $entityAttribute;
    private TmhRoute $route;

    public function __construct(TmhDatabase $database, TmhEntityAttribute $entityAttribute, TmhRoute $route)
    {
        $this->database = $database;
        $this->entityAttribute = $entityAttribute;
        $this->route = $route;
    }

    public function create(array $entity): array
    {
        $imageGroup = $this->database->entity('image_group', $entity['entity']);
        return [
            'entity_type' => $entity['type'],
            'images' => $this->imageGroupImages($imageGroup),
            'text_above' => $this->imageGroupTextAbove($imageGroup, $entity['type']),
            'text_below' => 'abc'
        ];
    }

    private function identifiedImageGroupTextAbove(array $imageGroup): string
    {
        $translation = str_replace('||identifier||', '', $imageGroup['translation']);
        $translation .= ' ' . $imageGroup['identifier'];
        return $translation;
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
                'route' => [
                    'href' => $route['href'],
                    'title' => $route['title']
                ]
            ];
        }
        return $images;
    }

    private function imageGroupTextAbove(array $imageGroup, string $type): string
    {
        return match($type) {
            'typed_routed_image_group' => $this->typedIdentifiedImageGroupTitle($imageGroup),
            default => $this->identifiedImageGroupTextAbove($imageGroup)
        };
    }

    private function typedIdentifiedImageGroupTitle(array $imageGroup): string
    {
        $type = $this->entityAttribute->attribute('key_value_key', 'h0krt2gk');
        $textAbove = $this->identifiedImageGroupTextAbove($imageGroup);
        return $type['translation'] . ' ' . $imageGroup['type'] . ' - ' . $textAbove;
    }
}