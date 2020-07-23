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
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var ClientInterface
     */
    protected $http;

    /**
     * @param $token
     * @param $environment
     */
    public function __construct($token, $environment = null)
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

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     * @return void
     */
    public function setEnvironment($environment)
    {
        if (! in_array($environment, Environment::ENVIRONMENTS)) {
            throw new InvalidEnvironmentException;
        }

        $this->environment = $environment;
    }

    /**
     * Get Http Client
     * @return ClientInterface
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * Set Http Client
     *
     * @param ClientInterface $http
     * @throws Exception
     */
    public function setHttp($http)
    {
        if (! $http instanceof ClientInterface) {
            throw new Exception('The parameter passed is not an instance of ' . ClientInterface::class);
        }

        $this->http = $http;
    }
}
