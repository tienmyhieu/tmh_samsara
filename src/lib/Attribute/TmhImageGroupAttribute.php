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
        return [
            'entity_type' => $entity['type'],
            'images' => $this->imageGroupImages($imageGroup['images']),
            'translation' => $this->imageGroupTitle($imageGroup, $entity['type'])
        ];
    }

    private function datedIdentifiedImageGroupTitle(array $imageGroup): string
    {
        return $imageGroup['date'] . ' - ' . $this->identifiedImageGroupTitle($imageGroup);
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

    private function identifiedImageGroupTitle(array $imageGroup): string
    {
        $translation = str_replace('||identifier||', '', $imageGroup['translation']);
        $translation .= ' ' . $imageGroup['identifier'];
        return $translation;
    }

    private function imageGroupTitle(array $imageGroup, string $type): string
    {
        return match($type) {
            'dated_identified_image_group' => $this->datedIdentifiedImageGroupTitle($imageGroup),
            'text_group_image_group' => $this->textGroupImageGroupTitle($imageGroup),
            default => $this->identifiedImageGroupTitle($imageGroup)
        };
    }

    private function textGroupImageGroupTitle(array $imageGroup): string
    {
        $entity = ['identifier' => '0', 'text_group' => $imageGroup['text_group'], 'type' => 'text_group'];
        $textGroup = $this->attributeFactory->create('text_group')->create($entity);
        return $textGroup['translation'];
    }
}
