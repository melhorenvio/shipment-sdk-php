<?php

namespace MelhorEnvio\Resources;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use MelhorEnvio\Enums\Endpoint;
use MelhorEnvio\Enums\Environment;
use MelhorEnvio\Exceptions\InvalidEnvironmentException;

abstract class Base implements Resource
{
    protected string $token;

    protected string $environment;

    protected ClientInterface $http;

    public function __construct(string $token, string $environment = null)
    {
        $this->token = $token;

        $this->setEnvironment($environment ? $environment : Environment::SANDBOX);

        $this->http = new Client([
            'base_uri' => Endpoint::ENDPOINTS[$this->environment] . '/api/' . Endpoint::VERSIONS[$this->environment] . '/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Accept'        => 'application/json',
            ]
        ]);
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function setEnvironment(string $environment): void
    {
        if (! in_array($environment, Environment::ENVIRONMENTS)) {
            throw new InvalidEnvironmentException;
        }

        $this->environment = $environment;
    }

    public function getHttp(): ClientInterface
    {
        return $this->http;
    }

    /**
     * @throws Exception
     */
    public function setHttp(ClientInterface $http): void
    {
        if (! $http instanceof ClientInterface) {
            throw new Exception('The parameter passed is not an instance of ' . ClientInterface::class);
        }

        $this->http = $http;
    }
}
