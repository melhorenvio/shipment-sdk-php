<?php

namespace MelhorEnvio\Resources;

use GuzzleHttp\ClientInterface;

interface Resource
{
    public function getHttp(): ClientInterface;

    public function setHttp(ClientInterface $http): void;
}
