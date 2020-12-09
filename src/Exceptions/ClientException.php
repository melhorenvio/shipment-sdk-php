<?php

namespace MelhorEnvio\Exceptions;

use Exception;

abstract class ClientException extends Exception
{
    public function __construct(\GuzzleHttp\Exception\ClientException $exception)
    {
        $response = $exception->getResponse();

        parent::__construct($response->getBody()->getContents(), $response->getStatusCode());
    }
}
