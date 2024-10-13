<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;

class TmhImageGroupAttribute implements TmhAttribute
{
    private const string SPACER_IMG = '35609f0ec63d40c9b0cca4f29cc089d0';

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
            'text_above' => $this->imageGroupTextAbove($imageGroup, $entity['type']),
            'text_below' => $this->imageGroupTextBelow($imageGroup, $entity['type']),
        ];
    }

    private function datedIdentifiedImageGroupTextAbove(array $imageGroup): string
    {
        return $imageGroup['date'] . ' - ' . $this->identifiedImageGroupTextAbove($imageGroup);
    }

    private function imageGroupImages(array $imageGroupImages): array
    {
        $images = [];
        foreach ($imageGroupImages as $imageGroupImage) {
            $rawImage = $this->database->entity('image', $imageGroupImage);
            $route = [];
            if ($rawImage['src'] !== self::SPACER_IMG) {
                $route = [
                    'href' => 'http://img1.tienmyhieu.com/images/1024/' . $rawImage['src'] . '.jpg',
                    'innerHtml' => '',
                    'name' => 'spec_' . $rawImage['identifier'],
                    'title' => $rawImage['alt']
                ];
            }
            $image = [
                'alt' => $rawImage['alt'],
                'src' => 'http://img1.tienmyhieu.com/images/128/' . $rawImage['src'] . '.jpg',
                'name' => 'spec_' . $rawImage['identifier'],
                'route' => $route
            ];
            $images[] = $image;
        }
        return $images;
    }

    private function identifiedImageGroupTextAbove(array $imageGroup): string
    {
        $translation = str_replace('||identifier||', '', $imageGroup['translation']);
        $translation .= ' ' . $imageGroup['identifier'];
        return $translation;
    }

    private function imageGroupTextAbove(array $imageGroup, string $type): string
    {
        return match($type) {
            'dated_identified_image_group' => $this->datedIdentifiedImageGroupTextAbove($imageGroup),
            'text_group_above_image_group' => $this->textGroupAboveImageGroupTextAbove($imageGroup),
            'text_group_below_image_group' => $this->textGroupBelowImageGroupTextAbove($imageGroup),
            'translated_image_group' => $imageGroup['translation'],
            'untitled_image_group' => '',
            default => $this->identifiedImageGroupTextAbove($imageGroup)
        };
    }

    private function imageGroupTextBelow(array $imageGroup, string $type): array
    {
        return match($type) {
            'text_group_below_image_group' => $this->textGroupBelowImageGroupTextBelow($imageGroup),
            default => []
        };
    }

    private function textGroupAboveImageGroupTextAbove(array $imageGroup): string
    {
        //$entity = ['identifier' => '0', 'text_group' => $imageGroup['text_group'], 'type' => 'text_group'];
        //$textGroup = $this->attributeFactory->create('text_group')->create($entity);
        return '';
    }

    private function textGroupBelowImageGroupTextAbove(array $imageGroup): string
    {
        return $imageGroup['translation'];
    }

    private function textGroupBelowImageGroupTextBelow(array $imageGroup): array
    {
        $below = [];
        $imageGroupTextGroups = explode(',', $imageGroup['text_group']);
        foreach ($imageGroupTextGroups as $imageGroupTextGroup) {
            $entity = ['identifier' => '0', 'text_group' => $imageGroupTextGroup, 'type' => 'text_group'];
            $textGroup = $this->attributeFactory->create('text_group')->create($entity);
            $below[] = $textGroup['translation'];
        }
        return $below;
    }
}
