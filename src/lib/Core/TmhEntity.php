<?php

namespace lib\Core;

use lib\Attribute\TmhAttributeFactory;

class TmhEntity
{
    private TmhAttributeFactory $componentFactory;
    private array $entity;
    private TmhEntityMetadata $entityMetadata;
    private array $metadata;
    private TmhEntityAttribute $entityAttribute;

    public function __construct(
        TmhAttributeFactory $componentFactory,
        TmhEntityMetadata $entityMetadata,
        TmhEntityAttribute $entityAttribute
    ) {
        $this->componentFactory = $componentFactory;
        $this->entityMetadata = $entityMetadata;
        $this->entityAttribute = $entityAttribute;

        $this->metadata = $this->entityMetadata->entityMetadata();
        $this->entity = [];
        $this->setAttributes();
    }

    public function get(): array
    {
        return $this->entity;
    }

    public function getWithMetadata(): array
    {
        return array_merge($this->metadata, $this->entity);
    }

    public function setAttributes(): void
    {
        //echo "<pre>" . $this->metadata['uuid'] . PHP_EOL . "</pre>";
        $attributes = [];
        $rawAttributes = $this->entityAttribute->entityAttributes($this->metadata['uuid']);
        foreach ($rawAttributes as $y => $attributeGroup) {
            foreach ($attributeGroup as $x => $attributeList) {
                foreach ($attributeList as $index => $attribute) {
                    $extraFields = [
                        'lang' => $this->metadata['lang'],
                        'documentTitle' => $this->metadata['documentTitle']
                    ];
                    $attribute = array_merge($attribute, $extraFields);
                    $attributes[$y][$x][$index] = $this->componentFactory->create($attribute['type'])->create($attribute);
                }
            }
        }
        $this->entity['attributes'] = $attributes;
    }
}
