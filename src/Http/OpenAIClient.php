<?php

namespace Alex8bits\AiClient\Http;

use Alex8bits\AiClient\Contracts\ClientInterface;
use Alex8bits\AiClient\DTO\ChatRequest;
use Alex8bits\AiClient\DTO\ChatResponse;
use Alex8bits\AiClient\Exceptions\ChatGptException;
use Illuminate\Support\Facades\Http;

class OpenAIClient implements ClientInterface
{
    public function __construct(
        protected RequestPayloadBuilder $builder
    ) {}

    public function sendChatRequest(ChatRequest $request): ChatResponse
    {
        $payload = $this->builder->build($request);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('chatgpt.api_key'),
        ])->post(config('chatgpt.endpoint'), $payload);

        if (!$response->successful()) {
            throw new ChatGPTException('ChatGPT API error: ' . $response->body());
        }

        return $this->builder->parseResponse($response->json());
    }
}