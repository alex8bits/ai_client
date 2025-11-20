<?php

namespace Alex8bits\AiClient\DTO;

class ChatChoice
{
    public function __construct(
        public ?string $content,
        public ?FunctionCall $functionCall,
        public string $finishReason
    ) {}
}