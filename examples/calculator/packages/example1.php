<?php

require "vendor/autoload.php";

use MelhorEnvio\Shipment;
use MelhorEnvio\Resources\Shipment\Package;
use MelhorEnvio\Enums\Service;
use MelhorEnvio\Enums\Environment;

// Create Shipment Instance
$shipment = new Shipment('14d96139bc175f899b0eb1ab069f3922ac34c73dafe48d9a3d77472226a2cd885167c0fab9ea4e74', Environment::PRODUCTION);

try {
    // Create Calculator Instance
    $calculator = $shipment->calculator();

    //Builds calculator payload
    $calculator->postalCode('01010010', '20271130');

    $calculator->setOwnHand();
    $calculator->setReceipt(false);
    $calculator->setCollect(true);

    $calculator->addPackages(
        new Package(12, 4, 17, 0.1, 6.0),
        new Package(12, 4, 17, 0.1, 6.0),
        new Package(12, 4, 17, 0.1, 6.0),
        new Package(12, 4, 17, 0.1, 6.0)

    );

    $calculator->addServices(
        Service::CORREIOS_PAC, Service::CORREIOS_SEDEX, Service::JADLOG_PACKAGE, Service::JADLOG_COM, Service::AZULCARGO_AMANHA
    );

    $quotations = $calculator->calculate();
}catch (Exception $exception) {
    die($exception);
}

print_r($quotations);
exit;
