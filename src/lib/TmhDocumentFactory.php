<?php

namespace lib;

use lib\HtmlComponent\TmhHtmlComponentFactory;

class TmhDocumentFactory
{
    private TmhElementFactory $elementFactory;
    private TmhHtmlComponentFactory $htmlComponentFactory;
    private TmhNodeTransformer $nodeTransformer;

    public function __construct(
        TmhElementFactory $elementFactory,
        TmhHtmlComponentFactory $htmlComponentFactory,
        TmhNodeTransformer $nodeTransformer
    ) {
        $this->elementFactory = $elementFactory;
        $this->htmlComponentFactory = $htmlComponentFactory;
        $this->nodeTransformer = $nodeTransformer;
    }

    public function create(array $entity): string
    {
        $nodes = $this->elementFactory->html([$this->head($entity), $this->body($entity)], $entity['lang']);
        return $this->nodeTransformer->toHtml($nodes);
    }

    private function body(array $entity): array
    {
        $marginLeft = $this->elementFactory->marginLeft();
        $marginRight = $this->elementFactory->marginRight();
        $childNodes = [];
        $componentGroups = [];
        foreach ($entity['components'] as $componentGroup) {
            $componentLists = [];
            foreach ($componentGroup as $componentList) {
                $components = [];
                foreach ($componentList as $component) {
                    $htmlComponent = $this->htmlComponentFactory->create($component['component_type']);
                    $components = array_merge($components, $htmlComponent->get($component));
                }
                $componentLists[] = $this->elementFactory->componentList($components);
            }
            $componentGroups[] = $this->elementFactory->componentGroup($componentLists);
        }
        $childNodes = $componentGroups;
//        foreach ($entity['components'] as $entityComponent) {
//            $htmlComponent = $this->htmlComponentFactory->create($entityComponent['component_type']);
//            $childNodes = array_merge($childNodes, $htmlComponent->get($entityComponent));
//        }
        $center = $this->elementFactory->center($childNodes);
        $body = $this->elementFactory->contentBody([$marginLeft, $center, $marginRight]);
        return $this->elementFactory->body([$body]);
    }

    private function head(array $entity): array
    {
        return $this->elementFactory->head($entity['description'], $entity['keywords'], $entity['documentTitle']);
    }
}
