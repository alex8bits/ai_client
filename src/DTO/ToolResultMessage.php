<?php

namespace Alex8bits\AiClient\DTO;

use Alex8bits\AiClient\Enums\Role;

class ToolResultMessage
{
    public function __construct(
        public string $tool_call_id,
        public string|array|null $content,
        public string $role = 'assistant',
        public string $type = 'tool_result',
    ) {}
}