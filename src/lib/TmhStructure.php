<?php

namespace lib;

class TmhStructure
{
    private array $structure;
    private TmhTranslation $translation;

    public function __construct(TmhJson $json, TmhTranslation $translation)
    {
        $this->translation = $translation;
        $this->structure = $this->translateStructure($json->structures());
        $this->assembleStructure();
    }

    public function entities(string $entity): array
    {
        return $this->activeEntities($this->structure[$entity]);
    }

    public function entity(string $entity, string $primaryKey): array
    {
        return $this->translateEntity($this->structure[$entity][$primaryKey]);
    }

    public function entityComponents(string $uuid): array
    {
        $exists = in_array($uuid, array_keys($this->entities('entity_component')));
        return $exists ? $this->structure['entity_component'][$uuid] : [];
    }

    public function inscription(string $uuid): string
    {
        return $this->translation->inscription($uuid);
    }

    public function items(string $list, string $uuid): array
    {
        return $this->filterEntities($this->entities($list . '_item'), $list, $uuid);
    }

    public function listItems(string $list, string $uuid): array
    {
        return $this->filterEntities($this->entities($list . '_list_item'), $list . '_list', $uuid);
    }

    private function activeEntities(array $entities): array
    {
        return $this->filterEntities($entities, 'active', '1');
    }

    private function assembleStructure(): void
    {
        $assembled = [];
        foreach ($this->entities('entity_component') as $c) {
            $c['type'] = $this->structure['component'][$c['component']]['type'];
            $assembled[$c['entity']][$c['y']][$c['x']][$c['index']] = $this->pruneEntity($c);
        }
        $this->structure['entity_component'] = $assembled;
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
        return in_array('translation', array_keys($entity)) && 0 < strlen($entity['translation']);
    }

    private function pruneEntity(array $entity): array
    {
        $keys = ['active', 'x', 'y', 'index'];
        foreach ($keys as $key) {
            if(array_key_exists($key, $entity)) {
                unset($entity[$key]);
            }
        }
        return $entity;
    }

    private function translateEntity(array $entity): array
    {
        if ($this->hasTranslation($entity)) {
            $entity['translation'] = $this->translation->translate($entity['translation']);
        }
        return $entity;
    }

    private function translateEntities(array $entities): array
    {
        return array_map(function ($entity) {
            return $this->translateEntity($entity);
        }, $entities);
    }

    private function translateStructure(array $structures): array
    {
        return array_map(function ($structure) {
            return $this->translateEntities($structure);
        }, $structures);
    }
}
