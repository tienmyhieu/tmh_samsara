<?php

namespace lib;

use lib\Core\TmhEntity;
use lib\Html\TmhHtmlDocumentFactory;

class TmhSamsara
{
    private TmhHtmlDocumentFactory $documentFactory;
    private TmhEntity $entity;

    public function __construct(TmhHtmlDocumentFactory $documentFactory, TmhEntity $entity)
    {
        $this->documentFactory = $documentFactory;
        $this->entity = $entity;
    }

    public function toHtml(): string
    {
        return $this->documentFactory->create($this->entity->getWithMetadata());
    }
}