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
        $fields = ['content', 'type', 'call_id', 'output', 'name', 'arguments'];
        $payload = [
            'model' => $request->model ?? config('chatgpt.model'),
            'input' => array_map(
                function ($m) use ($fields) {
                    $item = [];

                    if (!empty($m->role)) {
                        $item['role'] = $m->role->value;
                    }

                    foreach ($fields as $field) {
                        if (!empty($m->{$field})) {
                            $item[$field] = $m->{$field};
                        }
                    }

                    return $item;
                },
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

        foreach ($json['output'] ?? [] as $choice) {

            $functionCall = null;

            if ($choice['type'] === 'function_call') {
                $functionCall = new FunctionCall(
                    name: $choice['name'],
                    arguments: $choice['arguments'],
                    call_id: $choice['call_id']
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
