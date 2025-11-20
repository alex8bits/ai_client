<?php

namespace Alex8bits\AiClient\DTO;

class Tool
{
    public function __construct(
        public ToolFunction $function,
        public string $type = 'function',
    ) {}
}