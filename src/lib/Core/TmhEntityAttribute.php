<?php

namespace lib\Core;

class TmhEntityAttribute
{
    private array $attributes;
    private TmhTranslation $translation;

    public function __construct(TmhJson $json, TmhTranslation $translation)
    {
        $this->translation = $translation;
        $this->attributes = $this->translateEntityAttributes($json->attributes());
        $this->assembleAttributes();
    }

    public function attribute(string $entity, string $primaryKey): array
    {
        return $this->translateAttribute($this->attributes[$entity][$primaryKey]);
    }

    public function attributes(string $entity): array
    {
        return $this->activeAttributes($this->attributes[$entity]);
    }

    public function entityAttributes(string $uuid): array
    {
        $exists = in_array($uuid, array_keys($this->attributes('entity_attribute')));
        return $exists ? $this->attributes['entity_attribute'][$uuid] : [];
    }

    public function inscription(string $uuid): string
    {
        return $this->translation->inscription($uuid);
    }

    public function items(string $list, string $uuid): array
    {
        return $this->filterAttributes($this->attributes($list . '_item'), $list, $uuid);
    }

    public function languageItems(string $language, string $list, string $uuid): array
    {
        $attributes = $this->filterAttributes($this->attributes($list . '_item'), $list, $uuid);
        return $this->filterAttributes($attributes, 'language', $language);
    }

    public function listItems(string $list, string $uuid): array
    {
        return $this->filterAttributes($this->attributes($list . '_list_item'), $list . '_list', $uuid);
    }

    public function maximListItemCitations(string $uuid): array
    {
        return $this->filterAttributes($this->attributes('maxim_list_item_citation'), 'maxim_list_item', $uuid);
    }

    private function activeAttributes(array $attributes): array
    {
        return $this->filterAttributes($attributes, 'active', '1');
    }

    private function assembleAttributes(): void
    {
        $assembled = [];
        foreach ($this->attributes('entity_attribute') as $c) {
            $c['type'] = $this->attributes['attribute'][$c['attribute']]['type'];
            $assembled[$c['entity']][$c['y']][$c['x']][$c['index']] = $this->pruneAttribute($c);
        }
        $this->attributes['entity_attribute'] = $assembled;
    }

    private function filterAttributes(array $attributes, string $key, string $value): array
    {
        return array_filter($attributes, function($attribute) use($key, $value) {
            $hasKey = in_array($key, array_keys($attribute));
            return !$hasKey || $attribute[$key] == $value;
        });
    }

    private function hasTranslation(array $attribute): bool
    {
        return in_array('translation', array_keys($attribute)) && 0 < strlen($attribute['translation']);
    }

    private function pruneAttribute(array $attribute): array
    {
        $keys = ['active', 'x', 'y', 'index'];
        foreach ($keys as $key) {
            if(array_key_exists($key, $attribute)) {
                unset($attribute[$key]);
            }
        }
        return $attribute;
    }

    private function translateAttribute(array $attribute): array
    {
        if ($this->hasTranslation($attribute)) {
            $attribute['translation'] = $this->translation->translate($attribute['translation']);
        }
        return $attribute;
    }

    private function translateAttributes(array $attributes): array
    {
        return array_map(function ($attribute) {
            return $this->translateAttribute($attribute);
        }, $attributes);
    }

    private function translateEntityAttributes(array $entityAttributes): array
    {
        return array_map(function ($entityAttribute) {
            return $this->translateAttributes($entityAttribute);
        }, $entityAttributes);
    }
}
