<?php

namespace MelhorEnvio\Tests\Resources\Shipment;

use Generator;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use JsonException;
use MelhorEnvio\Exceptions\CalculatorException;
use MelhorEnvio\Exceptions\InvalidCalculatorPayloadException;
use MelhorEnvio\Resources\Resource;
use MelhorEnvio\Resources\Shipment\Calculator;
use MelhorEnvio\Resources\Shipment\Package;
use MelhorEnvio\Resources\Shipment\Product;
use MelhorEnvio\Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

class CalculatorTest extends TestCase
{
    /** @var Resource|MockInterface */
    private $resourceMock;
    private Calculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resourceMock = Mockery::mock(Resource::class);
        $this->calculator = new Calculator($this->resourceMock);
    }

    /**
     * @test
     * @small
     * @throws JsonException
     */
    public function it_returns_the_build_payload_as_json_when_the_calculator_is_casted_to_string(): void
    {
        $this->calculator->postalCode('00000000', '11111111');
        $this->calculator->setCollect(true);
        $this->calculator->setOwnHand(false);
        $this->calculator->setReceipt(true);
        $this->calculator->addServices(1, 2, 3);
        $this->calculator->addProducts(
            new Product('::product-1::', 1, 2, 3, 4, 5, 6),
            new Product('::product-2::', 7, 8, 9, 10, 11, 12)
        );

        $expectedPayload = json_encode([
            'from' => [
                'postal_code' => '00000000'
            ],
            'to' => [
                'postal_code' => '11111111'
            ],
            'options' => [
                'collect' => true,
                'own_hand' => false,
                'receipt' => true,
            ],
            'services' => '1,2,3',
            'products' => [
                [
                    'id' => '::product-1::',
                    'height' => 1,
                    'width' => 2,
                    'length' => 3,
                    'weight' => 4,
                    'insurance_value' => 5,
                    'quantity' => 6,
                ],
                [
                    'id' => '::product-2::',
                    'height' => 7,
                    'width' => 8,
                    'length' => 9,
                    'weight' => 10,
                    'insurance_value' => 11,
                    'quantity' => 12,
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $payload = (string)$this->calculator;

        $this->assertEquals($expectedPayload, $payload);
    }

    /**
     * @test
     * @small
     * @dataProvider postalCodeTypeProvider
     */
    public function it_can_add_postal_code(string $type): void
    {
        $postalCode = '00000000';

        $this->calculator->$type($postalCode);

        $payload = $this->calculator->getPayload();

        $this->assertEquals($postalCode, $payload[$type]['postal_code']);
    }

    /**
     * @test
     * @small
     * @dataProvider postalCodeTypeProvider
     */
    public function it_can_override_added_postal_code(string $type): void
    {
        $postalCode = '00000000';

        $this->calculator->$type('99999999');
        $this->calculator->$type($postalCode);

        $payload = $this->calculator->getPayload();

        $this->assertEquals($postalCode, $payload[$type]['postal_code']);
    }

    /**
     * @test
     * @small
     * @dataProvider postalCodeTypeProvider
     */
    public function it_throws_when_adding_an_invalid_postal_code(string $type): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($type);

        $this->calculator->$type('::invalid::');
    }

    /**
     * @test
     * @small
     */
    public function it_can_simulteneously_add_from_and_to_postal_codes(): void
    {
        $postalCodeFrom = '00000000';
        $postalCodeTo = '11111111';

        $this->calculator->postalCode($postalCodeFrom, $postalCodeTo);

        $payload = $this->calculator->getPayload();

        $this->assertEquals($postalCodeFrom, $payload['from']['postal_code']);
        $this->assertEquals($postalCodeTo, $payload['to']['postal_code']);
    }

    /**
     * @test
     * @small
     */
    public function it_can_override_simulteneously_added_from_and_to_postal_codes(): void
    {
        $postalCodeFrom = '00000000';
        $postalCodeTo = '11111111';

        $this->calculator->postalCode('99999999', '99999999');
        $this->calculator->postalCode($postalCodeFrom, $postalCodeTo);

        $payload = $this->calculator->getPayload();

        $this->assertEquals($postalCodeFrom, $payload['from']['postal_code']);
        $this->assertEquals($postalCodeTo, $payload['to']['postal_code']);
    }

    /**
     * @test
     * @small
     * @dataProvider invalidPostalCodesProvider
     */
    public function it_throws_when_adding_simulteneously_invalid_postal_codes(
        array $postalCodes,
        string $expectedExceptionMessage
    ): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $this->calculator->postalCode(...$postalCodes);
    }

    /**
     * @test
     * @small
     */
    public function it_can_add_a_package(): void
    {
        $package = new Package(1, 2, 3, 4, 5);
        $this->calculator->addPackage($package);

        $payload = $this->calculator->getPayload();

        $this->assertEquals($package->getHeight(), $payload['volumes'][0]['height']);
        $this->assertEquals($package->getWidth(), $payload['volumes'][0]['width']);
        $this->assertEquals($package->getLength(), $payload['volumes'][0]['length']);
        $this->assertEquals($package->getWeight(), $payload['volumes'][0]['weight']);
        $this->assertEquals($package->getInsurance(), $payload['volumes'][0]['insurance']);
    }

    /**
     * @test
     * @small
     */
    public function it_can_many_packages(): void
    {
        /** @var Package[] $packages */
        $packages = [];
        $packages[] = new Package(1, 2, 3, 4, 5);
        $packages[] = new Package(6, 7, 8, 9, 10);
        $packages[] = new Package(11, 12, 13, 14, 15);

        $this->calculator->addPackages(...$packages);

        $payload = $this->calculator->getPayload();

        $this->assertCount(3, $payload['volumes']);
        foreach ($packages as $i => $package) {
            $this->assertEquals($package->getHeight(), $payload['volumes'][$i]['height']);
            $this->assertEquals($package->getWidth(), $payload['volumes'][$i]['width']);
            $this->assertEquals($package->getLength(), $payload['volumes'][$i]['length']);
            $this->assertEquals($package->getWeight(), $payload['volumes'][$i]['weight']);
            $this->assertEquals($package->getInsurance(), $payload['volumes'][$i]['insurance']);
        }
    }

    /**
     * @test
     * @small
     */
    public function it_can_add_a_product(): void
    {
        $product = new Product('::id::', 1, 2, 3, 4, 5, 6);
        $this->calculator->addProduct($product);

        $payload = $this->calculator->getPayload();

        $this->assertEquals($product->getId(), $payload['products'][0]['id']);
        $this->assertEquals($product->getHeight(), $payload['products'][0]['height']);
        $this->assertEquals($product->getWidth(), $payload['products'][0]['width']);
        $this->assertEquals($product->getLength(), $payload['products'][0]['length']);
        $this->assertEquals($product->getWeight(), $payload['products'][0]['weight']);
        $this->assertEquals($product->getInsuranceValue(), $payload['products'][0]['insurance_value']);
        $this->assertEquals($product->getQuantity(), $payload['products'][0]['quantity']);
    }

    /**
     * @test
     * @small
     */
    public function it_can_add_many_products(): void
    {
        /** @var Product[] $products */
        $products = [];
        $products[] = new Product('::id::', 1, 2, 3, 4, 5, 6);
        $products[] = new Product('::id::', 7, 8, 9, 10, 11, 12);
        $products[] = new Product('::id::', 13, 14, 15, 16, 17, 18);

        $this->calculator->addProducts(...$products);

        $payload = $this->calculator->getPayload();

        $this->assertCount(3, $payload['products']);
        foreach ($products as $i => $product) {
            $this->assertEquals($product->getId(), $payload['products'][$i]['id']);
            $this->assertEquals($product->getHeight(), $payload['products'][$i]['height']);
            $this->assertEquals($product->getWidth(), $payload['products'][$i]['width']);
            $this->assertEquals($product->getLength(), $payload['products'][$i]['length']);
            $this->assertEquals($product->getWeight(), $payload['products'][$i]['weight']);
            $this->assertEquals($product->getInsuranceValue(), $payload['products'][$i]['insurance_value']);
            $this->assertEquals($product->getQuantity(), $payload['products'][$i]['quantity']);
        }
    }

    /**
     * @test
     * @small
     */
    public function it_can_add_a_service(): void
    {
        $service = 1;

        $this->calculator->addService($service);

        $payload = $this->calculator->getPayload();

        $this->assertEquals($service, $payload['services']);
    }

    /**
     * @test
     * @small
     */
    public function it_can_add_many_services(): void
    {
        $services = [1, 2, 3];

        $this->calculator->addServices(...$services);

        $payload = $this->calculator->getPayload();

        $this->assertEquals(implode(',', $services), $payload['services']);
    }

    /**
     * @test
     * @small
     * @dataProvider booleanProvider
     */
    public function it_can_set_receipt(bool $value): void
    {
        $this->calculator->setReceipt($value);

        $payload = $this->calculator->getPayload();

        $this->assertSame($value, $payload['options']['receipt']);
    }

    /**
     * @test
     * @small
     * @dataProvider booleanProvider
     */
    public function it_can_override_set_receipt(bool $value): void
    {
        $this->calculator->setReceipt(!$value);
        $this->calculator->setReceipt($value);

        $payload = $this->calculator->getPayload();

        $this->assertSame($value, $payload['options']['receipt']);
    }

    /**
     * @test
     * @small
     * @dataProvider booleanProvider
     */
    public function it_can_set_own_hand(bool $value): void
    {
        $this->calculator->setOwnHand($value);

        $payload = $this->calculator->getPayload();

        $this->assertSame($value, $payload['options']['own_hand']);
    }

    /**
     * @test
     * @small
     * @dataProvider booleanProvider
     */
    public function it_can_override_set_own_hand(bool $value): void
    {
        $this->calculator->setOwnHand(!$value);
        $this->calculator->setOwnHand($value);

        $payload = $this->calculator->getPayload();

        $this->assertSame($value, $payload['options']['own_hand']);
    }

    /**
     * @test
     * @small
     * @dataProvider booleanProvider
     */
    public function it_can_set_collect(bool $value): void
    {
        $this->calculator->setCollect($value);

        $payload = $this->calculator->getPayload();

        $this->assertSame($value, $payload['options']['collect']);
    }

    /**
     * @test
     * @small
     * @dataProvider booleanProvider
     */
    public function it_can_override_set_collect(bool $value): void
    {
        $this->calculator->setCollect(!$value);
        $this->calculator->setCollect($value);

        $payload = $this->calculator->getPayload();

        $this->assertSame($value, $payload['options']['collect']);
    }

    /**
     * @test
     * @small
     */
    public function it_returns_true_when_postal_code_is_valid(): void
    {
        $this->assertTrue($this->calculator->isValidPostalCode('00000000'));
    }

    /**
     * @test
     * @small
     */
    public function it_returns_false_when_validating_a_invalid_postal_code(): void
    {
        $this->assertFalse($this->calculator->isValidPostalCode('::invalid::'));
    }

    /**
     * @test
     * @small
     * @dataProvider calculatorWithoutPostalCodeProvider
     * @throws CalculatorException
     * @throws InvalidCalculatorPayloadException
     */
    public function it_throws_when_calculating_without_postal_codes(callable $setUpCalculator): void
    {
        $setUpCalculator($this->calculator);

        $this->expectException(InvalidCalculatorPayloadException::class);
        $this->expectExceptionMessage('The CEP is invalid.');

        $this->calculator->calculate();
    }

    /**
     * @test
     * @small
     * @throws CalculatorException
     * @throws InvalidCalculatorPayloadException
     */
    public function it_throws_when_calculating_without_product_nor_package(): void
    {
        $this->calculator->postalCode('00000000', '11111111');

        $this->expectException(InvalidCalculatorPayloadException::class);
        $this->expectExceptionMessage('There are no defined products or volumes.');

        $this->calculator->calculate();
    }

    /**
     * @test
     * @small
     * @throws CalculatorException
     * @throws InvalidCalculatorPayloadException
     */
    public function it_throws_when_calculating_with_product_and_package_at_the_same_time(): void
    {
        $product = new Product('::product-1::', 1, 2, 3, 4, 5, 6);
        $package = new Package(1, 2, 3, 4, 5);

        $this->calculator->postalCode('00000000', '11111111');
        $this->calculator->addProduct($product);
        $this->calculator->addPackage($package);

        $this->expectException(InvalidCalculatorPayloadException::class);
        $this->expectExceptionMessage('Products and volumes cannot be defined together in the same payload.');

        $this->calculator->calculate();
    }

    /**
     * @test
     * @small
     * @throws InvalidCalculatorPayloadException
     * @dataProvider httpClientStatusCodeProvider
     */
    public function it_throws_exception_when_an_http_client_error_occurs_while_calculating(int $statusCode): void
    {
        $clientException = ClientException::create(
            new Request('POST', '::uri::'),
            new Response($statusCode, [], null)
        );

        $this->resourceMock->shouldReceive('getHttp->post')->andThrow($clientException);

        $package = new Package(1, 2, 3, 4, 5);

        $this->calculator->postalCode('00000000', '11111111');
        $this->calculator->addPackage($package);

        $expectedException = new CalculatorException($clientException);

        try {
            $this->calculator->calculate();
        } catch (CalculatorException $e) {
            $this->assertEquals($expectedException, $e);

            return;
        }

        $this->fail(sprintf("%s was not thrown.", CalculatorException::class));
    }

    /**
     * @test
     * @small
     * @throws CalculatorException
     * @throws InvalidCalculatorPayloadException
     */
    public function it_uses_the_me_shipment_calculate_endpoint_when_calculating(): void
    {
        $package = new Package(1, 2, 3, 4, 5);

        $this->calculator->postalCode('00000000', '11111111');
        $this->calculator->addPackage($package);

        $url = null;
        $body = null;

        $this->resourceMock
            ->shouldReceive('getHttp->post')
            ->with(Mockery::capture($url), Mockery::capture($body))
            ->andReturn(new Response(200, [], ''));

        $this->calculator->calculate();

        $this->assertEquals('me/shipment/calculate', $url);
    }

    /**
     * @test
     * @small
     * @throws CalculatorException
     * @throws InvalidCalculatorPayloadException
     */
    public function it_uses_the_calculator_payload_as_request_body_when_calculating(): void
    {
        $package = new Package(1, 2, 3, 4, 5);

        $this->calculator->postalCode('00000000', '11111111');
        $this->calculator->addPackage($package);

        $url = null;
        $body = null;

        $this->resourceMock
            ->shouldReceive('getHttp->post')
            ->with(Mockery::capture($url), Mockery::capture($body))
            ->andReturn(new Response(200, [], ''));

        $this->calculator->calculate();

        $this->assertEquals($this->calculator->getPayload(), $body['json']);
    }

    /**
     * @test
     * @small
     * @throws CalculatorException
     * @throws InvalidCalculatorPayloadException
     * @throws JsonException
     */
    public function it_calculates(): void
    {
        $expectedJsonResponse = '[{"id":1,"name":"PAC","price":"27.48","custom_price":"27.48","discount":"0.00","currency":"R$","delivery_time":10,"delivery_range":{"min":9,"max":10},"custom_delivery_time":10,"custom_delivery_range":{"min":9,"max":10},"packages":[{"price":"27.48","discount":"0.00","format":"box","dimensions":{"height":6,"width":11,"length":18},"weight":"0.40","insurance_value":"0.00"}],"additional_services":{"receipt":false,"own_hand":true,"collect":false},"company":{"id":1,"name":"Correios","picture":"https:\/\/logistic.melhorenvio.work\/images\/shipping-companies\/correios.png"}}]';
        $expectedResponse = json_decode($expectedJsonResponse, true, 512, JSON_THROW_ON_ERROR);

        $this->resourceMock
            ->shouldReceive('getHttp->post')
            ->andReturn(new Response(200, [], $expectedJsonResponse));

        $package = new Package(1, 2, 3, 4, 5);

        $this->calculator->postalCode('00000000', '11111111');
        $this->calculator->addPackage($package);

        $sut = $this->calculator->calculate();

        $this->assertSame($expectedResponse, $sut);
    }

    public function postalCodeTypeProvider(): array
    {
        return [
            $type = 'to' => [$type],
            $type = 'from' => [$type],
        ];
    }

    public function invalidPostalCodesProvider(): array
    {
        return [
            'invalid to' => [
                ['::invalid::', '111111111'],
                'from',
            ],
            'invalid from' => [
                ['00000000', '::invalid::'],
                'to',
            ],
            'invalid to and from' => [
                ['::invalid::', '::invalid::'],
                'from',
            ],
        ];
    }

    public function booleanProvider(): array
    {
        return [
            'true' => [true],
            'false' => [false],
        ];
    }

    public function calculatorWithoutPostalCodeProvider(): array
    {
        return [
            'without from' => [
                function (Calculator $calculator) {
                    $calculator->to('00000000');
                }
            ],
            'without to' => [
                function (Calculator $calculator) {
                    $calculator->from('00000000');
                }
            ],
            'without both' => [
                static function (Calculator $calculator) {
                }
            ]
        ];
    }

    public function httpClientStatusCodeProvider(): Generator
    {
        foreach (range(400, 499) as $statusCode) {
            yield "status code $statusCode" => [$statusCode];
        }
    }
}
