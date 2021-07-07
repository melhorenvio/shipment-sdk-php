<?php

namespace MelhorEnvio\Resources\Shipment;

use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use MelhorEnvio\Exceptions\CalculatorException;
use MelhorEnvio\Exceptions\InvalidCalculatorPayloadException;
use MelhorEnvio\Exceptions\InvalidResourceException;
use MelhorEnvio\Exceptions\InvalidVolumeException;
use MelhorEnvio\Resources\Resource;
use MelhorEnvio\Validations\Location;
use MelhorEnvio\Validations\Number;

class Calculator
{
    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var Resource
     */
    protected $resource;

    /**
     * New Calculate instance.
     * @param $resource
     * @throws InvalidArgumentException
     */
    public function __construct($resource)
    {
        if (! $resource instanceof Resource) {
            throw new InvalidResourceException;
        }

        $this->resource = $resource;
    }

    /**
     * @param $postalCode
     * @throws InvalidArgumentException
     */
    public function from($postalCode)
    {
        $this->addPostalCodeInPayload('from', $postalCode);
    }

    /**
     * @param  $postalCode
     * @throws InvalidArgumentException
     */
    public function to($postalCode)
    {
        $this->addPostalCodeInPayload('to', $postalCode);
    }

    /**
     * @param int|string $from
     * @param int|string $to
     */
    public function postalCode($from, $to)
    {
        $this->addPostalCodeInPayload('from', $from);
        $this->addPostalCodeInPayload('to', $to);
    }

    /**
     * @param string $key
     * @param $postalCode
     * @throws InvalidArgumentException
     */
    protected function addPostalCodeInPayload($key, $postalCode)
    {
        if (! $this->isValidPostalCode($postalCode)) {
            throw new InvalidArgumentException($key);
        }

        $this->payload[$key]['postal_code'] = $postalCode;
    }

    /**
     * @param  $products
     * @throws InvalidArgumentException
     */
    public function addProducts($products)
    {
        $products = is_array($products) ? $products : func_get_args();

        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    /**
     * @param  $packages
     * @throws InvalidArgumentException
     */
    public function addPackages($packages)
    {
        $packages = is_array($packages) ? $packages : func_get_args();

        foreach ($packages as $package) {
            $this->addPackage($package);
        }
    }

    /**
     * @param Package $package
     * @throws InvalidVolumeException
     */
    public function addPackage($package)
    {
        if (! $this->isValidPackage($package)) {
            throw new InvalidVolumeException('package');
        }

        $this->payload['volumes'][] = $package->toArray();
    }

    /**
     * @param Product $product
     * @throws InvalidVolumeException
     */
    public function addProduct($product)
    {
        if (! $this->isValidProduct($product)) {
            throw new InvalidVolumeException('product');
        }

        $this->payload['products'][] = $product->toArray();
    }

    /**
     * @param $services
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
     * @param int $service
     * @throws InvalidArgumentException
     */
    public function addService($service)
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
     * Add Receipt in payload options
     * @param  $receipt
     * @throws InvalidArgumentException
     */
    public function setReceipt($receipt = true)
    {
        if (! is_bool($receipt)) {
            throw new InvalidArgumentException('receipt');
        }

        $this->payload['options']['receipt'] = $receipt;
    }

    /**
     * Add own hand in payload options
     * @param  $ownHand
     * @throws InvalidArgumentException
     */
    public function setOwnHand($ownHand = true)
    {
        if (! is_bool($ownHand)) {
            throw new InvalidArgumentException('own_hand');
        }

        $this->payload['options']['own_hand'] = $ownHand;
    }

    /**
     * Add collect in payload options
     * @param bool $collect
     * @throws InvalidArgumentException
     */
    public function setCollect($collect = true)
    {
        if (! is_bool($collect)) {
            throw new InvalidArgumentException('collect');
        }

        $this->payload['options']['collect'] = $collect;
    }

    /**
     * @param $postalCode
     * @return bool
     */
    public function isValidPostalCode($postalCode)
    {
        return Location::isPostalCode($postalCode);
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function isValidProduct($product)
    {
        return $product instanceof Product && $product->isValid();
    }

    /**
     * @param Package $package
     * @return bool
     */
    public function isValidPackage($package)
    {
        return $package instanceof Package && $package->isValid();
    }

    /**
     * @param $service
     * @return bool
     */
    protected function isValidService($service)
    {
        return Number::isPositiveInteger($service);
    }

    /**
     * @return void
     * @throws InvalidCalculatorPayloadException
     */
    protected function validatePayload()
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

    /**
     * @return array
     */
    public function getPayload()
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

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->getPayload());
    }
}
