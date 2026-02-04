<?php

namespace App\DTO;

final readonly class BrokerDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $phone,
        public string $mobile,
    )
    {
    }
}
