<?php


namespace MelhorEnvio\Exceptions;

use Exception;

abstract class ClientException extends Exception
{
    public function __construct(Exception $e)
    {
        $response = $e->getResponse();

        parent::__construct($response->getBody()->getContents(), $response->getStatusCode());
    }
}
