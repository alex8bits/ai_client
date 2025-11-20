<?php

namespace Alex8bits\AiClient\DTO;

use Alex8bits\AiClient\Enums\Role;

class Message
{
    public function __construct(
        public Role $role,
        public string|array|null $content
    ) {}
}