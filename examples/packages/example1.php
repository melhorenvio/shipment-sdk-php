<?php

require "vendor/autoload.php";

use MelhorEnvio\Shipment;
use MelhorEnvio\Resources\Shipment\Package;
use MelhorEnvio\Enums\Service;
use MelhorEnvio\Enums\Environment;

// Create Shipment Instance
$shipment = new Shipment('your-token', Environment::PRODUCTION);

try {
    // Create Calculator Instance
    $calculator = $shipment->calculator();

    //Builds calculator payload
    $calculator->postalCode('01010010', '20271130');

    $calculator->setOwnHand();
    $calculator->setReceipt(false);
    $calculator->setCollect(false);

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
    //Proper exception context
}

print_r($quotations);
exit;
