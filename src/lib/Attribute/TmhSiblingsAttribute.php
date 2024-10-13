<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;
use lib\Core\TmhRoute;

class TmhSiblingsAttribute implements TmhAttribute
{
    private TmhDatabase $database;
    private TmhRoute $route;

    public function __construct(TmhDatabase $database, TmhRoute $route)
    {
        $this->database = $database;
        $this->route = $route;
    }

    public function create(array $entity): array
    {
        $locales = $this->database->rawEntities('locale');
        return [
            'component_type' => $entity['type'],
            'siblings' => $this->route->siblings($locales)
        ];
    }
}