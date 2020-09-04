<?php

require "vendor/autoload.php";

use MelhorEnvio\Shipment;
use MelhorEnvio\Resources\Shipment\Product;
use MelhorEnvio\Enums\Environment;

// Create Shipment Instance
$shipment = new Shipment('your-token', Environment::PRODUCTION);

try {
    // Create Calculator Instance
    $calculator = $shipment->calculator();

    // Builds the calculator payload.
    $calculator->postalCode('01010010', '20271130');

    $calculator->setOwnHand();
    $calculator->setReceipt(false);
    $calculator->setCollect(false);

    $calculator->addProducts(
        new Product(uniqid(), 40, 30, 50, 10.00, 100.0, 1),
        new Product(uniqid(), 5, 1, 10, 0.1, 50.0, 1)
    );

    $quotations = $calculator->calculate();
} catch (Exception $exception) {
    //Proper exception context
}

print_r($quotations);
exit;
