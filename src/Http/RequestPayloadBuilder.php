<?php

namespace Alex8bits\AiClient\Http;

use Alex8bits\AiClient\DTO\ChatChoice;
use Alex8bits\AiClient\DTO\ChatRequest;
use Alex8bits\AiClient\DTO\ChatResponse;
use Alex8bits\AiClient\DTO\FunctionCall;

class RequestPayloadBuilder
{
    public function build(ChatRequest $request): array
    {
        $payload = [
            'model' => $request->model ?? config('chatgpt.model'),
            'input' => array_map(
                fn($m) => [
                    'role'    => $m->role->value,
                    'content' => $m->content,
                ],
                $request->messages
            ),
        ];

        if ($request->tools) {
            $payload['tools'] = array_map(function ($tool) {
                return [
                    'type'          => $tool['type'],
                    'name'          => $tool['function']['name'],
                    'description'   => $tool['function']['description'],
                    'parameters'    => $tool['function']['parameters'],
                ];
            }, $request->tools);
        }

        $payload['tool_choice'] = "auto";
        $payload['temperature'] = $request->temperature ?? config('chatgpt.temperature');

        return $payload;
    }

    /**
     * Парсит response JSON → ChatResponse DTO
     */
    public function parseResponse(array $json): ChatResponse
    {
        $choices = [];

        foreach ($json['choices'] ?? [] as $choice) {

            $functionCall = null;

            if (!empty($choice['message']['tool_calls'][0]['function'])) {
                $fc = $choice['message']['tool_calls'][0]['function'];
                $functionCall = new FunctionCall(
                    name: $fc['name'],
                    arguments: $fc['arguments']
                );
            }

            $choices[] = new ChatChoice(
                content: $choice['message']['content'] ?? null,
                functionCall: $functionCall,
                finishReason: $choice['finish_reason'] ?? ''
            );
        }

        return new ChatResponse(
            choices: $choices,
            raw: $json
        );
    }
}
