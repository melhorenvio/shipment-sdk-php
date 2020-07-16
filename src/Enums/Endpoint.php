<?php

namespace MelhorEnvio\Enums;

class Endpoint
{
    /**
     * API Endpoint
     * @const array
     */
    const ENDPOINTS = [
        'production' => 'https://melhorenvio.com.br',
        'sandbox' => 'https://sandbox.melhorenvio.com.br',
    ];

    /**
     * API Version
     * @const array
     */
    const VERSIONS = [
        'production' => 'v2',
        'sandbox' => 'v2',
    ];

    /**
     * Production endpoint
     * @const string
     */
    const PRODUCTION = self::ENDPOINTS['production'];

    /**
     * Sandbox endpoint
     * @const string
     */
    const SANDBOX = self::ENDPOINTS['sandbox'];
}
