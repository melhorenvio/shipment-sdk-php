<?php

namespace MelhorEnvio\Enums;

class Service
{
    /**
     * Services - CORREIOS
     * @var int
     */
    const CORREIOS_PAC = 1;
    const CORREIOS_SEDEX = 2;
    const CORREIOS_ESEDEX = 14;
    const CORREIOS_MINI = 17;

    /**
     * Services - JADLOG
     * @var int
     */
    const JADLOG_PACKAGE = 3;
    const JADLOG_COM = 4;

    /**
     * Services - VIABRASIL
     * @var int
     */
    const VIABRASIL_AEREO = 8;
    const VIABRASIL_RODOVIARIO = 9;

    /**
     * Services - LATAM CARGO
     * @var int
     */
    const LATAMCARGO_PROXIMODIA = 10;
    const LATAMCARGO_PROXIMOVOO = 11;
    const LATAMCARGO_JUNTOS = 12;

    /**
     * Services - AZUL CARGO
     * @var int
     */
    const AZULCARGO_AMANHA = 15;
    const AZULCARGO_ECOMMERCE = 16;
}
