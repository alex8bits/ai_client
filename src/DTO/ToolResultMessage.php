<?php

namespace Alex8bits\AiClient\DTO;

use Alex8bits\AiClient\Enums\Role;

class ToolResultMessage
{
    public function __construct(
        public string $call_id,
        public string $output,
        public ?string $type = 'function_call_output',
    ) {}
}
