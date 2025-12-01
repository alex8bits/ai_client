<?php

return [
    'assistant'     => env('CHATGPT_ASSISTANT_ID', null),
    'endpoint'     => env('CHATGPT_URL', 'https://api.openai.com/v1/responses'),
    'api_key'      => env('CHATGPT_API_KEY'),
    'model'        => 'gpt-4o-mini',
    'temperature'  => 0.7,
    'max_tokens'   => 2048,

    'base_prompt'  => null,
    'instructions' => [
        // 'dialog_key' => 'Instruction...',
    ],

    'use_proxy' => false,
    'proxy_url' => env('CHATGPT_PROXY_URL'),

    'tools' => [
        // ключ — имя инструмента, по которому мы будем обращаться
        /*'get_weather' => [
            'type' => 'function',
            'function' => [
                'name' => 'get_weather',
                'description' => 'Получить погоду по названию города',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'city' => [
                            'type' => 'string',
                            'description' => 'Название города'
                        ],
                    ],
                    'required' => ['city']
                ],
            ],
        ],

        'find_flights' => [
            'type' => 'function',
            'function' => [
                'name' => 'find_flights',
                'description' => 'Поиск авиабилетов',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'from' => ['type' => 'string'],
                        'to'   => ['type' => 'string'],
                        'date' => ['type' => 'string'],
                    ],
                    'required' => ['from', 'to']
                ],
            ],
        ],*/
    ],
];