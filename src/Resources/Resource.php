<?php

namespace MelhorEnvio\Resources;

use GuzzleHttp\ClientInterface;

interface Resource
{
    /**
     * @return ClientInterface
     */
    public function getHttp();

    /**
     * @param ClientInterface $http
     * @return void
     */
    public function setHttp($http);
}
