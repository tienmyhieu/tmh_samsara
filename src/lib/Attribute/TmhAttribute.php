<?php

namespace lib\Attribute;

interface TmhAttribute
{
    public function create(array $entity): array;
}