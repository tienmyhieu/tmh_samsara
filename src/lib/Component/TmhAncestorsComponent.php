<?php

namespace lib\Component;

use lib\TmhRoute;

class TmhAncestorsComponent implements TmhComponent
{
    private TmhRoute $route;

    public function __construct(TmhRoute $route)
    {
        $this->route = $route;
    }

    public function get(array $entity): array
    {
        return [
            'component_type' => $entity['type'],
            'ancestors' => $this->route->ancestors()
        ];
    }
}
