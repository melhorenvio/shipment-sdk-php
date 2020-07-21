<?php

namespace MelhorEnvio\Enums;

class Service
{
    /**
     * Services - CORREIOS
     * @var int
     */
    public const CORREIOS_PAC = 1;
    public const CORREIOS_SEDEX = 2;
    public const CORREIOS_ESEDEX = 14;
    public const CORREIOS_MINI = 17;

    /**
     * Services - JADLOG
     * @var int
     */
    public const JADLOG_PACKAGE = 3;
    public const JADLOG_COM = 4;

    /**
     * Services - JAMEF
     * @var int
     */
    public const JAMEF_RODOVIARIO = 7;

    /**
     * Services - VIABRASIL
     * @var int
     */
    public const VIABRASIL_AEREO = 8;
    public const VIABRASIL_RODOVIARIO = 9;

    /**
     * Services - LATAM CARGO
     * @var int
     */
    public const LATAMCARGO_PROXIMODIA = 10;
    public const LATAMCARGO_PROXIMOVOO = 11;
    public const LATAMCARGO_CONVENCIONAL = 12;

    /**
     * Services - AZUL CARGO
     * @var int
     */
    public const AZULCARGO_AMANHA = 15;
    public const AZULCARGO_ECOMMERCE = 16;
}
