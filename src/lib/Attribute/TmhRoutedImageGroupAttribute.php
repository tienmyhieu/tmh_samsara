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
        $imageGroup = array_merge(['image_group_list_item_translation' => $entity['translation']], $imageGroup);
        return [
            'entity_type' => $entity['type'],
            'images' => $this->imageGroupImages($imageGroup),
            'text_above' => $this->imageGroupTextAbove($imageGroup, $entity['type']),
            'text_below' => []
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
                'name' => 'spec_' . $rawImage['identifier'],
                'src' => 'http://img1.tienmyhieu.com/images/128/' . $rawImage['src'] . '.jpg',
                'route' => [
                    'href' => $route['href'],
                    'name' => 'spec_' . $rawImage['identifier'],
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
            'identified_routed_image_group' => $this->identifiedImageGroupTextAbove($imageGroup),
            default => $imageGroup['translation']
        };
    }

    private function typedIdentifiedImageGroupTitle(array $imageGroup): string
    {
        $textAbove = $this->identifiedImageGroupTextAbove($imageGroup);
        return $imageGroup['image_group_list_item_translation'] . ' - ' . $textAbove;
    }
}