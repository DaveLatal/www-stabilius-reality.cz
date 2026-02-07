<?php

namespace App\DTO;

final readonly class CategoryCountingsDTO
{
    public function __construct(
        public int $all,
        public int $byty,
        public int $domy,
        public int $pozemky,
        public int $komercni,
        public int $ostatni
    )
    {
    }
}
