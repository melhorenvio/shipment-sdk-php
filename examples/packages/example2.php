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

    // Builds the calculator payload.
    $calculator->postalCode('01010010', '20271130');
    $calculator->addPackage(new Package(2, 10, 15, 1, 6.0));
    $calculator->addServices(Service::CORREIOS_PAC, Service::CORREIOS_SEDEX, Service::CORREIOS_MINI);

    $quotations = $calculator->calculate();
} catch (Exception $exception) {
    //Proper exception context
}

print_r($quotations);
exit;
