<?php

namespace Alex8bits\AiClient\Contracts;

use Alex8bits\AiClient\DTO\ChatRequest;
use Alex8bits\AiClient\DTO\ChatResponse;

interface ClientInterface
{
    public function sendChatRequest(ChatRequest $request): ChatResponse;
}