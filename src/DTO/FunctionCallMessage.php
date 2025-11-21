<?php

namespace Alex8bits\AiClient\DTO;

use Alex8bits\AiClient\Enums\Role;

class FunctionCallMessage
{
    public function __construct(
        public string $call_id,
        public string $arguments,
        public string $name,
        public ?string $type = 'function_call',
    ) {}
}
