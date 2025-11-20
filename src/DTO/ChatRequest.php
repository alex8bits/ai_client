<?php

namespace Alex8bits\AiClient\DTO;

class ChatRequest
{
    /**
     * @param Message[] $messages
     * @param Tool[] $tools
     */
    public function __construct(
        public array $messages,
        public array $tools = [],
        public ?string $model = null,
        public ?float $temperature = null,
        public ?int $maxTokens = null
    ) {}
}