<?php

namespace lib;

class TmhDocumentFactory
{
    private TmhElementFactory $elementFactory;

    public function __construct(TmhElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function create(array $entity): array
    {
        return $this->elementFactory->html([$this->head($entity), $this->body($entity)], $entity['lang']);
    }

    private function body(array $entity): array
    {
        $marginLeft = $this->elementFactory->marginLeft();
        $marginRight = $this->elementFactory->marginRight();
        $childNodes = [$this->elementFactory->pageTitle($entity['pageTitle'])];
        $br = $this->elementFactory->br();
        foreach ($entity['sections'] as $section) {
            $sections = [];
            if (0 < strlen($section['translation'])) {
                $childNodes[] = $this->elementFactory->sectionTitle($section['translation']);
            }
            foreach ($section['lists'] as $list) {
                $listChildNodes = [];
                if (0 < strlen($list['translation'])) {
                    $listChildNodes[] = $this->elementFactory->listTitle($list['translation']);
                }
                foreach ($list['entities'] as $entity) {
                    $entityChildNodes = [];
                    if ($entity['entity_type'] == 'route') {
                        $entityChildNodes[] = $this->elementFactory->listItemLink(
                            $entity['href'],
                            $entity['innerHtml'],
                            $entity['title']
                        );
                    }
                    if ($entity['entity_type'] == 'text') {
                        $entityChildNodes[] = $this->elementFactory->span($entity['translation']);
                    }
                    if ($entity['entity_type'] == 'entity_citation') {
                        $entityChildNodes[] = $this->elementFactory->span($entity['translation']);
                    }
                    if ($entity['entity_type'] == 'image_group') {
                        $imageGroupNodes = [$this->elementFactory->span($entity['translation']), $br];
                        foreach ($entity['images'] as $image) {
                            $imageGroupNodes[] = $this->elementFactory->linkedImage(
                                $entity['route']['href'],
                                $image,
                                $entity['route']['title']
                            );
                        }
                        $imageGroupNodes[] = $br;
                        $entityChildNodes[] = $this->elementFactory->imageGroup($imageGroupNodes);
                    }
                    $listChildNodes[] = $this->elementFactory->listItem($entityChildNodes);
                }
                $sections[] = $this->elementFactory->subSection($listChildNodes);
            }
            $childNodes[] = $this->elementFactory->subSections($sections);
        }
        $center = $this->elementFactory->center($childNodes);
        $body = $this->elementFactory->contentBody([$marginLeft, $center, $marginRight]);
        return $this->elementFactory->body([$body]);
    }

    private function head(array $entity): array
    {
        return $this->elementFactory->head($entity['description'], $entity['keywords'], $entity['documentTitle']);
    }
}