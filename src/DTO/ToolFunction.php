<?php

namespace Alex8bits\AiClient\DTO;

class ToolFunction
{
    public function __construct(
        public string $name,
        public ?string $description,
        public array $parameters
    ) {}
}