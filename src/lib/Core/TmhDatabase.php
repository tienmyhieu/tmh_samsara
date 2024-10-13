<?php

namespace lib\Core;

class TmhDatabase
{
    private array $database;
    private TmhTranslation $translation;

    public function __construct(TmhJson $json, TmhTranslation $translation)
    {
        $this->database = $json->database();
        $this->translation = $translation;
        $this->translation->initializeLocale($this->rawEntities('locale'));
        $this->database['image'] = $this->translateImages($this->database['image']);
        $this->database['upload'] = $this->translateImages($this->database['upload']);
    }

    public function component(string $primaryKey): array
    {
        return $this->entity('component', $primaryKey);
    }

    public function entity(string $entity, string $primaryKey): array
    {
        return $this->translateEntity($this->database[$entity][$primaryKey]);
    }

    public function entities(string $entity, array $filter): array
    {
        $entities = $this->rawEntities($entity);
        if (!empty($filter)) {
            $entities = $this->filterEntities($entities, $filter['key'], $filter['value']);
        }

        return $this->translateEntities($entities);
    }

    public function rawEntities(string $entity): array
    {
        return $this->filterEntities($this->database[$entity], 'active', '1');
    }

    private function filterEntities(array $entities, string $key, string $value): array
    {
        return array_filter($entities, function($entity) use($key, $value) {
            $hasKey = in_array($key, array_keys($entity));
            return !$hasKey || $entity[$key] == $value;
        });
    }

    private function hasTranslation(array $entity): bool
    {
        $hasKey = in_array('translation', array_keys($entity));
        return $hasKey && 0 < strlen($entity['translation']);
    }

    private function translateEntity(array $entity): array
    {
        $hasTranslation = $this->hasTranslation($entity);
        if ($hasTranslation) {
            $entity['translation'] = $this->translation->translate($entity['translation']);
        }
        return $entity;
    }

    private function translateImages(array $images): array
    {
        $translated = [];
        foreach ($images as $primaryKey => $image) {
            $alt = '';
            foreach ($image['alt'] as $imageAlt) {
                $alt .= $this->translation->translate($imageAlt) . ' ';
            }
            $alt = substr($alt, 0, -1);
            $image['alt'] = $alt;
            $translated[$primaryKey] = $image;
        }
        return $translated;
    }

    private function translateEntities(array $entities): array
    {
        return array_map(function ($entity) {
            return $this->translateEntity($entity);
        }, $entities);
    }
}
