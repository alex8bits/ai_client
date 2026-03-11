<?php

namespace Alex8bits\AiClient\Services;

use Alex8bits\AiClient\Contracts\ClientInterface;
use Alex8bits\AiClient\DTO\ChatRequest;
use Alex8bits\AiClient\DTO\Message;
use Alex8bits\AiClient\DTO\Tool;
use Alex8bits\AiClient\DTO\ToolFunction;
use Alex8bits\AiClient\Enums\Role;

class ChatService
{
    public function __construct(protected ClientInterface $client)
    {}

    /**
     * Отправка запроса к ChatGPT
     *
     * @param Message[] $messages
     * @param Tool[] $tools — инструменты, переданные вручную (не из конфига)
     * @param string|null $instructionKey — ключ инструкции в config('chatgpt.instructions')
     * @param string[] $toolKeys — инструменты, подгружаемые из config('chatgpt.tools')
     * @param boolean $use_base_prompt - надо ли использовать при запросе базовый промрт
     */
    public function send(
        array $messages,
        array $tools = [],
        ?string $instructionKey = null,
        array $toolKeys = [],
        bool $use_base_prompt = true,
        ?string $custom_prompt = null,
    ) {
        // 1. Инструкция для конкретного диалога
        if ($instructionKey && ($inst = config("chatgpt.instructions.$instructionKey"))) {
            array_unshift($messages, new Message(
                Role::System,
                $inst
            ));
        }

        // 2. Базовый системный промпт
        $prompt = null;
        if ($use_base_prompt && $basePrompt = config('chatgpt.base_prompt')) {
            $prompt = $basePrompt . PHP_EOL;
        }
        if ($custom_prompt) {
            $prompt .= $custom_prompt;
        }
        if ($prompt) {
            array_unshift($messages, new Message(
                Role::System,
                $prompt
            ));
        }

        // 3. Подгружаем tools по ключам из config
        if (!empty($toolKeys)) {
            $toolsFromConfig = $this->getToolsFromConfig($toolKeys);
            $tools = array_merge($tools, $toolsFromConfig);
        }

        // 4. Формируем DTO запроса
        $request = new ChatRequest(
            messages: $messages,
            tools: $tools,
        );

        // 5. Отправка клиенту
        return $this->client->sendChatRequest($request);
    }

    /**
     * Получение инструментов из конфигурации.
     *
     * @param string[] $keys
     * @return Tool[]
     */
    protected function getToolsFromConfig(array $keys): array
    {
        $configTools = config('chatgpt.tools', []);

        $result = [];

        foreach ($keys as $key) {
            if (!isset($configTools[$key])) {
                continue;
            }

            $data = $configTools[$key];

            $result[] = new Tool(
                function: new ToolFunction(
                    name: $data['function']['name'],
                    description: $data['function']['description'] ?? null,
                    parameters: $data['function']['parameters'] ?? []
                ),
                type: $data['type'] ?? 'function'
            );
        }

        return $result;
    }
}