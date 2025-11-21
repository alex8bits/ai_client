<?php

namespace Alex8bits\AiClient\DTO;

class FunctionCall
{
    public function __construct(
        public string $name,
        public string $arguments,
        public string $call_id
    ) {}
}