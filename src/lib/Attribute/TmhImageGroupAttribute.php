<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;

class TmhImageGroupAttribute implements TmhAttribute
{
    private TmhAttributeFactory $attributeFactory;
    private TmhDatabase $database;

    public function __construct(TmhAttributeFactory $attributeFactory, TmhDatabase $database)
    {
        $this->attributeFactory = $attributeFactory;
        $this->database = $database;
    }

    public function create(array $entity): array
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
        $entity = ['identifier' => '0', 'text_group' => $imageGroup['text_group'], 'type' => 'text_group'];
        $textGroup = $this->attributeFactory->create('text_group')->create($entity);
        return $textGroup['translation'];
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
}
