<?php

namespace lib\Core;

class TmhEntityMetadata
{
    private array $entityMetadata;
    private TmhRoute $route;
    private array $routeEntity;

    public function __construct(TmhRoute $route)
    {
        $this->route = $route;

        $this->routeEntity = $this->route->currentEntity();
        $this->setMetadata();
    }

    public function entityMetadata(): array
    {
        return $this->entityMetadata;
    }

    private function description(): string
    {
        $description = $this->routeEntity['title'];
        $href = substr($this->routeEntity['href'], 1);
        if (0 < strlen($href)) {
            $parts = explode('/', str_replace('_', ' ', $href));
            $description = implode(' ', $parts);
        }
        return $description;
    }

    private function documentTitle(): string
    {
        $documentTitle = $this->routeEntity['title'];
        $href = substr($this->routeEntity['href'], 1);
        if (0 < strlen($href)) {
            $parts = explode('/', str_replace('_', ' ', $href));
            $defaultTitle = $this->routeEntity['defaultTitle'];
            if (1 == count($parts)) {
                $documentTitle = $defaultTitle . ' ' . $this->routeEntity['title'];
            }
        }
        return $documentTitle;
    }

    private function keywords(): string
    {
        $keywords = $this->routeEntity['title'];
        $href = substr($this->routeEntity['href'], 1);
        if (0 < strlen($href)) {
            $parts = explode('/', str_replace('_', ' ', $href));
            $keywords = implode(', ', $parts);
        }
        return $keywords;
    }

    private function setMetadata(): void
    {
        $this->entityMetadata['description'] = $this->description();
        $this->entityMetadata['documentTitle'] = $this->documentTitle();
        $this->entityMetadata['keywords'] = $this->keywords();
        $this->entityMetadata['lang'] = $this->routeEntity['lang'];
        $this->entityMetadata['defaultTitle'] = $this->routeEntity['defaultTitle'];
        $this->entityMetadata['uuid'] = $this->routeEntity['uuid'];
        $this->entityMetadata['type'] = $this->routeEntity['entity'];
    }
}
