<?php

namespace Alex8bits\AiClient;

use Alex8bits\AiClient\Contracts\ClientInterface;
use Alex8bits\AiClient\Http\OpenAIClient;
use Alex8bits\AiClient\Http\ProxyClient;
use Alex8bits\AiClient\Http\RequestPayloadBuilder;
use Alex8bits\AiClient\Services\ChatService;
use Illuminate\Support\ServiceProvider;

class ChatGPTServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Публикация конфига
        $this->mergeConfigFrom(__DIR__ . '/../config/chatgpt.php', 'chatgpt');

        // Регистрируем builder
        $this->app->singleton(RequestPayloadBuilder::class, function () {
            return new RequestPayloadBuilder();
        });

        // Выбор клиента: прямой или через прокси
        $this->app->bind(ClientInterface::class, function ($app) {
            $builder = $app->make(RequestPayloadBuilder::class);

            if (config('chatgpt.use_proxy')) {
                return new ProxyClient($builder);
            }

            return new OpenAIClient($builder);
        });

        // Регистрируем ChatService
        $this->app->singleton(ChatService::class, function ($app) {
            return new ChatService(
                $app->make(ClientInterface::class)
            );
        });
    }

    public function boot()
    {
        // Публикация конфига
        $this->publishes([
            __DIR__ . '/../config/chatgpt.php' => config_path('chatgpt.php'),
        ], 'config');
    }
}