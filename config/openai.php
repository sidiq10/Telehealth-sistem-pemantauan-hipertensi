<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
    'max_tokens' => env('OPENAI_MAX_TOKENS', 500),
    'temperature' => env('OPENAI_TEMPERATURE', 0.7),
    'timeout' => 60,
    'retry_attempts' => 3,
];
