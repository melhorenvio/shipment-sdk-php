<?php

namespace MelhorEnvio\Tests\Resources\Shipment;

use InvalidArgumentException;
use MelhorEnvio\Resources\Shipment\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @var Product
     */
    private $product;

    protected function setUp()
    {
        $this->product = new Product(uniqid(), 40, 30, 50, 10.00, 100.0, 1);
    }

    /**
     * @test
     */
    public function isValidQuantityOfProducts()
    {
        $quantity = $this->product->getQuantity();

        $this->product->setQuantity($quantity);

        self::assertEquals(1, $quantity);
    }

    /**
     * @test
     */
    public function isInvalidQuantityOfProducts()
    {
        $this->expectException(InvalidArgumentException::class);

        $invalidProduct = new Product(uniqid(), 40, 30, 50, 10.00, 100.0, -2);

        $invalidQuantity = $invalidProduct->getQuantity();

        $invalidProduct->setQuantity($invalidQuantity);
    }


}
