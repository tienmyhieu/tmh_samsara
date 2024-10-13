<?php
namespace lib;

class TmhDatabase
{
    private array $database = [];

    public function __construct(TmhJson $json)
    {
        $this->load($json);
        $this->setRelations();
    }

    public function entities(string $entity, array $queries): array
    {
        $entities = $this->filterEntities($this->database[$entity], 'active', '1');
        foreach ($queries as $query) {
            foreach ($query as $key => $value) {
                $entities = $this->filterEntities($entities, $key, $value);
            }
        }
        return $entities;
    }

    public function entity(string $entity, string $primaryKey): array
    {
        $entities = $this->database[$entity];
        return in_array($primaryKey, array_keys($entities)) ? $entities[$primaryKey]: [];
    }

    private function filterEntities(array $entities, string $key, string $value): array
    {
        return array_filter($entities, function($entity) use($key, $value) {
            $hasKey = in_array($key, array_keys($entity));
            return !$hasKey || $this->filterEntityByValue($entity, $key, $value);
        });
    }

    private function filterEntityByValue(array $entity, string $key, string $value): bool
    {
        return is_array($entity[$key]) ? $this->filterRelationByValue($entity[$key], $value) : $entity[$key] == $value;
    }

    private function filterRelationByValue(array $relation, string $value): bool
    {
        return in_array('name', array_keys($relation)) && $relation['name'] == $value;
    }

    private function load(TmhJson $json): void
    {
        foreach (scandir(__DIR__ . '/../tmh_database') as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $fileName = str_replace('.json', '', $file);
            $this->database[$fileName] = $json->load(__DIR__ . '/../tmh_database/', $fileName);
        }
    }

    private function setEntityRelations(array $attributes): array
    {
        $transformed = [];
        foreach ($attributes as $key => $value) {
            if (in_array($key, array_keys($this->database))) {
                if (in_array($value, array_keys($this->database[$key]))) {
                    $value = $this->setEntityRelations($this->database[$key][$value]);
                }
            }
            $transformed[$key] = $value;
        }
        return $transformed;
    }

    private function setRelations(): void
    {
        $transformed = [];
        foreach ($this->database as $entityName => $entities) {
            foreach ($entities as $primaryKey => $attributes) {
                $transformed[$entityName][$primaryKey] = $this->setEntityRelations($attributes);
            }
        }
        $this->database = $transformed;
    }

    private function prune(array $row): array
    {
        $fields = ['active', 'comment', 'obverse'];
        foreach ($fields as $field) {
            if (in_array($field, array_keys($row))) {
                unset($row[$field]);
            }
        }
        return $row;
    }
}
