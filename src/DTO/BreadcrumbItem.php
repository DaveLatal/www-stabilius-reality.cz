<?php

// src/Dto/BreadcrumbItem.php
namespace App\DTO;

final readonly class BreadcrumbItem
{
    public function __construct(
        public string $label,
        public ?string $url = null,
    ) {}
}

