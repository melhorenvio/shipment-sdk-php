<?php

require "vendor/autoload.php";

use MelhorEnvio\Enums\Service;
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

    $products = [
        ["id" => "z", "width" => 30, "height" => 40, "length" => 50, "weight" => 10.00, "quantity" => 10, "insurance_value" => 100.00],
        ["id" => "y", "width" => 1, "height" => 5, "length" => 10, "weight" => 0.10, "quantity" => 10, "insurance_value" => 50.0],
        ["id" => "x", "width" => 18, "height" => 12, "length" => 4, "weight" => 0.050, "quantity" => 1, "insurance_value" => 65.90],
    ];

    foreach ($products as $product) {
        $calculator->addProduct(
            new Product($product['id'], $product['height'], $product['width'], $product['length'], $product['weight'], $product['insurance_value'], $product['quantity'])
        );
    }

    $calculator->addServices(Service::JADLOG_PACKAGE, Service::JADLOG_COM, Service::VIABRASIL_RODOVIARIO, Service::LATAMCARGO_PROXIMODIA);

    $quotations = $calculator->calculate();
} catch (Exception $exception) {
    //Proper exception context
}

print_r($quotations);
exit;
