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

    // Define Payload origin and destiny.
    $calculator->postalCode('01010010', '20271130');

    // Define Payload Product
    $calculator->addProduct(new Product(uniqid(), 40, 30, 50, 10.00, 100.0, 1));

    // Performs calculation
    $quotations = $calculator->calculate();
} catch (Exception $exception) {
    //Proper exception context
}

print_r($quotations);
exit;
