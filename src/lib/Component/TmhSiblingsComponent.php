<?php

namespace lib\Component;

use lib\TmhDatabase;
use lib\TmhRoute;

class TmhSiblingsComponent implements TmhComponent
{
    private TmhDatabase $database;
    private TmhRoute $route;

    public function __construct(TmhDatabase $database, TmhRoute $route)
    {
        $this->database = $database;
        $this->route = $route;
    }

    public function get(array $entity): array
    {
        $locales = $this->database->rawEntities('locale');
        return [
            'component_type' => $entity['type'],
            'siblings' => $this->route->siblings($locales)
        ];
    }
}