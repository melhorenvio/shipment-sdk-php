<?php

namespace MelhorEnvio\Enums;

class Endpoint
{
    const ENDPOINTS = [
        'production' => 'https://melhorenvio.com.br',
        'sandbox' => 'https://sandbox.melhorenvio.com.br',
    ];

    const VERSIONS = [
        'production' => 'v2',
        'sandbox' => 'v2',
    ];

    const PRODUCTION = self::ENDPOINTS['production'];

    const SANDBOX = self::ENDPOINTS['sandbox'];
}
