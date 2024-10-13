<?php

namespace lib\Attribute;

use lib\Core\TmhRoute;

class TmhAncestorsAttribute implements TmhAttribute
{
    private TmhRoute $route;

    public function __construct(TmhRoute $route)
    {
        $this->route = $route;
    }

    public function create(array $entity): array
    {
        return [
            'component_type' => $entity['type'],
            'ancestors' => $this->route->ancestors()
        ];
    }
}
