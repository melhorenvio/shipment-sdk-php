<?php

namespace MelhorEnvio\Enums;


class Environment
{
    /**
     * Available Environments
     * @var array
     */
    const ENVIRONMENTS = [
        self::PRODUCTION, self::SANDBOX,
    ];

    /**
     * Production environment
     * @var string
     */
    const PRODUCTION ='production';

    /**
     * Sandbox environment
     * @var string
     */
    const SANDBOX ='sandbox';
}

