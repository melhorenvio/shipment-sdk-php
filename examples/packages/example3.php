<?php

require "vendor/autoload.php";

use MelhorEnvio\Enums\Service;
use MelhorEnvio\Shipment;
use MelhorEnvio\Resources\Shipment\Package;
use MelhorEnvio\Enums\Environment;

// Create Shipment Instance
$shipment = new Shipment('your-token', Environment::PRODUCTION);

try {
    // Create Calculator Instance
    $calculator = $shipment->calculator();

    // Define Payload origin and destiny.
    $calculator->from('01010010');
    $calculator->to('20271130');

    // Define Payload Options
    $calculator->setReceipt();
    $calculator->setOwnHand();
    $calculator->setCollect();

    // Define Payload Packages
    $packages = [
        ["weight" => 0.1, "width" => 2, "height" => 5, "length" => 10, "insurance" => 50],
        ["weight" => 10, "width" => 34, "height" => 40, "length" => 50, "insurance" => 50]
    ];

    foreach ($packages as $package) {
        $calculator->addPackage(
            new Package($package['height'], $package['width'], $package['length'], $package['weight'], $package['insurance'])
        );
    }

    // Define Payload Services
    $calculator->addServices(Service::CORREIOS_MINI, Service::CORREIOS_SEDEX, Service::CORREIOS_PAC);

    // Performs calculation
    $quotations = $calculator->calculate();
} catch (Exception $exception) {
    //Proper exception context
}

foreach ($quotations as $quotation) {
    print("Service: " . $quotation['name'] . " ");

    if (isset($quotation['error'])) {
        echo $quotation['error'];
        continue;
    }

    print("Custom Price: " . $quotation['custom_price'] . " ");
    print("Custom Delivery Time: " . $quotation['custom_delivery_time'] . PHP_EOL);
}
