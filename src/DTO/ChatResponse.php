<?php

namespace Alex8bits\AiClient\DTO;

class ChatResponse
{
    /**
     * @param ChatChoice[] $choices
     */
    public function __construct(
        public array $choices,
        public array $raw
    ) {}
}