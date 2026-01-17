<?php

namespace App\DTO;

final readonly class Breadcrumbs
{
    /**
     * @param BreadcrumbItem[] $items
     */
    public function __construct(
        public array $items,
    ) {}
}

