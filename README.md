📘 AI Client (Laravel ChatGPT Integration)

Интеграция ChatGPT / OpenAI / Proxy-серверов в Laravel
с поддержкой function calling (tools), инструкций, базового системного промпта, и DTO.

Пакет построен на принципе независимости от провайдера:
можно использовать OpenAI напрямую или через любой свой Proxy API.

✨ Возможности

✔ Поддержка ChatGPT (OpenAI) и любых кастомных API-прокси

✔ Чистые DTO вместо массивов

✔ Автодобавление системных инструкций

✔ Поддержка tools / function calling

✔ Настройка инструментов через config

✔ Полностью совместимо с Laravel 10 / 11

✔ Фасад ChatGPT

✔ Расширяемость и кастомизация клиента

✔ Тестируемость (mock ClientInterface)

## 📦 Установка
```bash
composer require alex8bits/ai_client
```

Пакет регистрируется автоматически через Laravel Package Discovery.

## 🔧 Публикация конфига (опционально)
```bash
php artisan vendor:publish --provider="Alex8bits\AiClient\ChatGPTServiceProvider" --tag=config
```

Будет создан:

config/chatgpt.php

Пример фрагмента config/chatgpt.php
```
return [
    'model' => 'gpt-4.1',
    'temperature' => 0.2,
    'max_tokens' => 2048,
    'base_prompt' => "Ты — умный и вежливый AI ассистент.",

    'instructions' => [
        'weather' => "Ты отвечаешь о погоде кратко, строго по факту.",
    ],

    'tools' => [
        'get_weather' => [
            'type' => 'function',
            'function' => [
                'name' => 'getWeather',
                'description' => 'Получение погоды по городу',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'city' => ['type' => 'string'],
                    ],
                    'required' => ['city'],
                ],
            ],
        ],
    ],
];
```
🧠 Базовый пример использования
```
use ChatGPT;
use Alex8bits\AiClient\DTO\Message;
use Alex8bits\AiClient\Enums\Role;

$response = ChatGPT::send([
new Message(Role::User, 'Привет! Как дела?'),
]);

echo $response->text(); // или разбор choices
```

📌 Пример использования инструкции (instructionKey)
```
$response = ChatGPT::send(
messages: [
new Message(Role::User, "Какая сегодня погода?")
],
instructionKey: 'weather'
);
```


Будет добавлена системная инструкция:
```
Ты отвечаешь о погоде кратко, строго по факту.
```

🛠 Пример использования tools из конфига

Используем tool по ключу:
```
$response = ChatGPT::send(
messages: [
new Message(Role::User, "Скажи погоду в Москве"),
],
toolKeys: ['get_weather']
);
```

ChatGPT сможет вызвать функцию:
```
{
    "type": "function",
    "function": {
        "name": "getWeather",
        "arguments": "{ \"city\": \"Москва\" }"
    }
}
```

🔧 Пример ручного добавления tool (без конфига)
```
use Alex8bits\AiClient\DTO\Tool;
use Alex8bits\AiClient\DTO\ToolFunction;

$tool = new Tool(
    type: 'function',
    function: new ToolFunction(
        name: 'calculateSum',
        description: 'Сложение двух чисел',
        parameters: [
            'type' => 'object',
            'properties' => [
                'a' => ['type' => 'number'],
                'b' => ['type' => 'number'],
            ],
        'required' => ['a', 'b']
        ]
    )
);

$response = ChatGPT::send(
    messages: [
        new Message(Role::User, "Сложи 5 и 7")
    ],
    tools: [$tool]
);
```
🧩 Пример смешанных tools (ручные + из конфига)
```
ChatGPT::send(
    messages: [ new Message(Role::User, "Комбинированный запрос") ],
    tools: [
        new Tool(
            type: 'function',
            function: new ToolFunction(
            name: 'customLogic',
            description: 'Моя логика',
            parameters: []
            )
        )
    ],
    toolKeys: ['get_weather']
);
```
🏗 Пример работы с ProxyClient (кастомный API)

В config/chatgpt.php:
```
'client' => Alex8bits\AiClient\Clients\ProxyClient::class,

'use_proxy' => true,
'proxy_url' => env('CHATGPT_PROXY_URL'),
```

Теперь запросы пойдут через ваш API.

🎯 Ответ ChatGPT — DTO
```
$response->choices[0]->content;
$response->choices[0]->functionCall?->name;
$response->raw;     // полный JSON
$response->text();  // удобный доступ к тексту
```