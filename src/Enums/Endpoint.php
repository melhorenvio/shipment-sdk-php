<?php

namespace MelhorEnvio\Enums;

class Endpoint
{
    /**
     * API Endpoint
     * @var array
     */
    const ENDPOINTS = [
        'production' => 'https://melhorenvio.com.br',
        'sandbox' => 'https://sandbox.melhorenvio.com.br',
    ];

    /**
     * API Version
     * @var array
     */
    const VERSIONS = [
        'production' => 'v2',
        'sandbox' => 'v2',
    ];

    /**
     * Production endpoint
     * @var string
     */
    const PRODUCTION = self::ENDPOINTS['production'];

    /**
     * Sandbox endpoint
     * @var string
     */
    const SANDBOX = self::ENDPOINTS['sandbox'];
}
