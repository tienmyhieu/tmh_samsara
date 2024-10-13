<?php

namespace lib\Html;

use lib\HtmlComponent\TmhHtmlComponentFactory;

class TmhHtmlDocumentFactory
{
    private TmhHtmlElementFactory $elementFactory;
    private TmhHtmlComponentFactory $htmlComponentFactory;
    private TmhHtmlNodeTransformer $nodeTransformer;

    public function __construct(
        TmhHtmlElementFactory   $elementFactory,
        TmhHtmlComponentFactory $htmlComponentFactory,
        TmhHtmlNodeTransformer $nodeTransformer
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
        foreach ($entity['attributes'] as $attributeGroup) {
            $attributeGroupCount = count($attributeGroup);
            if ($attributeGroupCount == 1) {
                $onlyAttributeGroup = $attributeGroup[1][1];
                $htmlComponent = $this->htmlComponentFactory->create($onlyAttributeGroup['component_type']);
                $component = $htmlComponent->get($onlyAttributeGroup);
                $childNodes[] = $this->elementFactory->component([$component[0]]);
            } else {
                $componentLists = [];
                foreach ($attributeGroup as $attributeList) {
                    $components = [];
                    foreach ($attributeList as $attribute) {
                        $htmlComponent = $this->htmlComponentFactory->create($attribute['component_type']);
                        $components = array_merge($components, $htmlComponent->get($attribute));
                    }
                    $componentLists[] = $this->elementFactory->componentList($components);
                }
                $childNodes[] = $this->elementFactory->componentGroup($componentLists);
            }
        }
        $center = $this->elementFactory->center($childNodes);
        $body = $this->elementFactory->contentBody([$marginLeft, $center, $marginRight]);
        return $this->elementFactory->body([], [$body]);
    }

    private function head(array $entity): array
    {
        return $this->elementFactory->head($entity['description'], $entity['keywords'], $entity['documentTitle']);
    }
}
