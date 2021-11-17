<?php

namespace MelhorEnvio\Enums;

class Service
{
    const CORREIOS_PAC = 1;
    const CORREIOS_SEDEX = 2;
    const CORREIOS_ESEDEX = 14;
    const CORREIOS_MINI = 17;

    const JADLOG_PACKAGE = 3;
    const JADLOG_COM = 4;

    const VIABRASIL_AEREO = 8;
    const VIABRASIL_RODOVIARIO = 9;

    const LATAMCARGO_PROXIMODIA = 10;
    const LATAMCARGO_PROXIMOVOO = 11;
    const LATAMCARGO_JUNTOS = 12;

    const AZULCARGO_AMANHA = 15;
    const AZULCARGO_ECOMMERCE = 16;
}
