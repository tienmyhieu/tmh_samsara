<?php

namespace lib\Attribute;

use lib\Core\TmhRoute;

class TmhNumberedShadowRouteAttribute implements TmhAttribute
{
    private TmhRoute $route;

    public function __construct(TmhRoute $route)
    {
        $this->route = $route;
    }

    public function create(array $entity): array
    {
        $route = $this->route->routeEntityByKey($entity['entity']);
        return [
            'entity_type' => $entity['type'],
            'href' => $route['href'],
            'innerHtml' => $this->shadowInnerHtml($entity),
            'title' => $route['title']
        ];
    }

    private function shadowInnerHtml(array $entity): string
    {
        $title = str_replace('||identifier||', '', $entity['translation']);
        return $entity['identifier'] . '. ' . $title;
    }
}
