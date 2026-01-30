<?php

namespace Alex8bits\AiClient\Facades;

use Alex8bits\AiClient\DTO\ChatResponse;
use Alex8bits\AiClient\Services\ChatService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ChatResponse send(array $messages, array $tools = [], ?string $instructionKey = null, bool $use_base_prompt = true)
 */
class ChatGPT extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ChatService::class;
    }
}