<?php

namespace App\DTO;

class MailMessageDTO
{
    public function __construct(
        public ?string $firstname,
        public ?string $lastname,
        public ?string $email,
        public ?string $phone,
        public ?string $message,
    ) {}

}
