<?php

namespace MelhorEnvio\Resources\Shipment;

use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use MelhorEnvio\Exceptions\CalculatorException;
use MelhorEnvio\Exceptions\InvalidCalculatorPayloadException;
use MelhorEnvio\Exceptions\InvalidResourceException;
use MelhorEnvio\Exceptions\InvalidVolumeException;
use MelhorEnvio\Resources\Shipment\Package;
use MelhorEnvio\Resources\Resource;
use MelhorEnvio\Validations\Location;
use MelhorEnvio\Validations\Number;

class Calculator
{
    protected array $payload = [];

    protected Resource $resource;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Resource $resource)
    {
        if (! $resource instanceof Resource) {
            throw new InvalidResourceException;
        }

        $this->resource = $resource;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function from(string $postalCode)
    {
        $this->addPostalCodeInPayload('from', $postalCode);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function to(string $postalCode)
    {
        $this->addPostalCodeInPayload('to', $postalCode);
    }

    public function postalCode(string $from, string $to)
    {
        $this->addPostalCodeInPayload('from', $from);
        $this->addPostalCodeInPayload('to', $to);
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function addPostalCodeInPayload(string $key, string $postalCode)
    {
        if (! $this->isValidPostalCode($postalCode)) {
            throw new InvalidArgumentException($key);
        }

        $this->payload[$key]['postal_code'] = $postalCode;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addProducts(Product $products)
    {
        $products = is_array($products) ? $products : func_get_args();

        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addPackages(Package $packages)
    {
        $packages = is_array($packages) ? $packages : func_get_args();

        foreach ($packages as $package) {
            $this->addPackage($package);
        }
    }

    /**
     * @throws InvalidVolumeException
     */
    public function addPackage(Package $package)
    {
        if (! $this->isValidPackage($package)) {
            throw new InvalidVolumeException('package');
        }

        $this->payload['volumes'][] = $package->toArray();
    }

    /**
     * @throws InvalidVolumeException
     */
    public function addProduct(Product $product)
    {
        if (! $this->isValidProduct($product)) {
            throw new InvalidVolumeException('product');
        }

        $this->payload['products'][] = $product->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addServices($services)
    {
        $services = is_array($services) ? $services : func_get_args();

        foreach ($services as $service) {
            $this->addService($service);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addService(int $service)
    {
        if (! $this->isValidService($service)) {
            throw new InvalidArgumentException('service');
        }

        if (! isset($this->payload['services'])) {
            $this->payload['services'] = $service;
        } else {
            $this->payload['services'] .= ',' . $service;
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setReceipt(bool $receipt = true)
    {
        if (! is_bool($receipt)) {
            throw new InvalidArgumentException('receipt');
        }

        $this->payload['options']['receipt'] = $receipt;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setOwnHand(bool $ownHand = true)
    {
        if (! is_bool($ownHand)) {
            throw new InvalidArgumentException('own_hand');
        }

        $this->payload['options']['own_hand'] = $ownHand;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setCollect(bool $collect = true)
    {
        if (! is_bool($collect)) {
            throw new InvalidArgumentException('collect');
        }

        $this->payload['options']['collect'] = $collect;
    }

    public function isValidPostalCode(string $postalCode): bool
    {
        return Location::isPostalCode($postalCode);
    }

    public function isValidProduct(Product $product): bool
    {
        return $product instanceof Product && $product->isValid();
    }

    public function isValidPackage(Package $package): bool
    {
        return $package instanceof Package && $package->isValid();
    }

    protected function isValidService(int $service): bool
    {
        return Number::isPositiveInteger($service);
    }

    protected function validatePayload(): void
    {
        if (empty($this->payload['from']['postal_code']) || empty($this->payload['to']['postal_code'])) {
            throw new InvalidCalculatorPayloadException('The CEP is invalid.');
        }

        if (empty($this->payload['volumes']) && empty($this->payload['products'])) {
            throw new InvalidCalculatorPayloadException('There are no defined products or volumes.');
        }

        if (! empty($this->payload['volumes']) && ! empty($this->payload['products'])) {
            throw new InvalidCalculatorPayloadException('Products and volumes cannot be defined together in the same payload.');
        }
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @throws InvalidCalculatorPayloadException|CalculatorException
     */
    public function calculate()
    {
        $this->validatePayload();

        try {
            $response = $this->resource->getHttp()->post('me/shipment/calculate', [
                'json' => $this->payload,
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (ClientException $exception) {
            throw new CalculatorException($exception);
        }
    }

    public function __toString():  ?string
    {
        return json_encode($this->getPayload());
    }
}
