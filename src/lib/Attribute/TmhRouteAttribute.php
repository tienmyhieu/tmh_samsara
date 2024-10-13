<?php

namespace lib\Attribute;

use lib\Core\TmhRoute;

class TmhRouteAttribute implements TmhAttribute
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
            'innerHtml' => str_replace('_', ' ', $route['innerHtml']),
            'title' => $route['title']
        ];
    }
}
