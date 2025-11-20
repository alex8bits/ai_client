<?php

namespace Alex8bits\AiClient\Enums;

enum Model: string
{
    case GPT4_1 = 'gpt-4.1';
    case GPT4_1Mini = 'gpt-4.1-mini';
    case GPT4oMini = 'gpt-4o-mini';
    case Custom = 'custom';
}
