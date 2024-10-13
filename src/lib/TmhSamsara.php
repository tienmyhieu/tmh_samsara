<?php

namespace lib;

class TmhSamsara
{
    private TmhDocumentFactory $documentFactory;
    private TmhEntity $entity;

    public function __construct(TmhDocumentFactory $documentFactory, TmhEntity $entity)
    {
        $this->documentFactory = $documentFactory;
        $this->entity = $entity;
    }

    public function toHtml(): string
    {
        return $this->documentFactory->create($this->entity->getWithMetadata());
    }
}