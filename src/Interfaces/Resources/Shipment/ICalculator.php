<?php

namespace MelhorEnvio\Interfaces\Resources\Shipment;

use InvalidArgumentException;
use MelhorEnvio\Exceptions\CalculatorException;
use MelhorEnvio\Exceptions\InvalidCalculatorPayloadException;
use MelhorEnvio\Exceptions\InvalidVolumeException;
use MelhorEnvio\Resources\Shipment\Package;
use MelhorEnvio\Resources\Shipment\Product;

interface ICalculator
{
    /**
     * @throws InvalidArgumentException
     */
    public function from(string $postalCode);

    /**
     * @throws InvalidArgumentException
     */
    public function to(string $postalCode);

    public function postalCode(string $from, string $to);

    /**
     * @throws InvalidArgumentException
     */
    public function addProducts(Product $products);

    /**
     * @throws InvalidArgumentException
     */
    public function addPackages(Package $packages);

    /**
     * @throws InvalidVolumeException
     */
    public function addPackage(Package $package);

    /**
     * @throws InvalidVolumeException
     */
    public function addProduct(Product $product);

    /**
     * @throws InvalidArgumentException
     */
    public function addServices($services);

    /**
     * @throws InvalidArgumentException
     */
    public function addService(int $service);

    /**
     * @throws InvalidArgumentException
     */
    public function setReceipt(bool $receipt = true);

    /**
     * @throws InvalidArgumentException
     */
    public function setOwnHand(bool $ownHand = true);

    /**
     * @throws InvalidArgumentException
     */
    public function setCollect(bool $collect = true);

    public function isValidPostalCode(string $postalCode): bool;

    public function isValidProduct(Product $product): bool;

    public function isValidPackage(Package $package): bool;

    public function getPayload(): array;

    /**
     * @throws InvalidCalculatorPayloadException|CalculatorException
     */
    public function calculate();
}
