<?php


namespace MelhorEnvio\Enums;


class Service
{
    /**
     * Services - CORREIOS
     * @const int
     */
    public const CORREIOS_PAC = 1;
    public const CORREIOS_SEDEX = 2;
    public const CORREIOS_ESEDEX = 14;
    public const CORREIOS_MINI = 17;

    /**
     * Services - JADLOG
     * @const int
     */
    public const JADLOG_PACKAGE = 3;
    public const JADLOG_COM = 4;

    /**
     * Services - JAMEF
     * @const int
     */
    public const JAMEF_RODOVIARIO = 7;

    /**
     * Services - VIABRASIL
     * @const int
     */
    public const VIABRASIL_AEREO = 8;
    public const VIABRASIL_RODOVIARIO = 9;

    /**
     * Services - LATAM CARGO
     * @const int
     */
    public const LATAMCARGO_PROXIMODIA = 10;
    public const LATAMCARGO_PROXIMOVOO = 11;
    public const LATAMCARGO_CONVENCIONAL = 12;

    /**
     * Services - AZUL CARGO
     * @const int
     */
    public const AZULCARGO_AMANHA = 15;
    public const AZULCARGO_ECOMMERCE = 16;
}
