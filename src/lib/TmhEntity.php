<?php

namespace lib;

use lib\Component\TmhComponentFactory;

class TmhEntity
{
    private TmhComponentFactory $componentFactory;
    private TmhDatabase $database;
    private array $entity;
    private TmhEntityMetadata $entityMetadata;
    private array $metadata;
    private TmhStructure $structure;

    public function __construct(
        TmhComponentFactory $componentFactory,
        TmhDatabase $database,
        TmhEntityMetadata $entityMetadata,
        TmhStructure $structure
    ) {
        $this->componentFactory = $componentFactory;
        $this->database = $database;
        $this->entityMetadata = $entityMetadata;
        $this->structure = $structure;

        $this->metadata = $this->entityMetadata->entityMetadata();
        $this->entity = [];
        $this->setComponents();
    }

    public function get(): array
    {
        return $this->entity;
    }

    public function getWithMetadata(): array
    {
        return array_merge($this->metadata, $this->entity);
    }

    public function setComponents(): void
    {
        //echo "<pre>" . $this->metadata['uuid'] . PHP_EOL . "</pre>";
        $components = [];
        $rawComponents = $this->structure->entityComponents($this->metadata['uuid']);
        foreach ($rawComponents as $y => $componentGroup) {
            foreach ($componentGroup as $x => $componentList) {
                foreach ($componentList as $index => $component) {
                    $extraFields = ['documentTitle' => $this->metadata['documentTitle']];
                    $component = array_merge($component, $extraFields);
                    $components[$y][$x][$index] = $this->componentFactory->create($component['type'])->get($component);
                }
            }
        }
        $this->entity['components'] = $components;
    }
}
