<?php

namespace MelhorEnvio\Tests;

use GuzzleHttp\ClientInterface;
use MelhorEnvio\Enums\Endpoint;
use MelhorEnvio\Enums\Environment;
use MelhorEnvio\Resources\Base;
use Mockery;

abstract class BaseResourceTest extends TestCase
{
    private const TEST_TOKEN = '::token::';

    abstract protected function getClass(): string;

    /**
     * @test
     * @small
     */
    public function it_constructs_with_sandbox_environment_as_default(): void
    {
        $class = $this->getClass();

        /** @var Base $sut */
        $sut = new $class(self::TEST_TOKEN);

        $this->assertEquals(Environment::SANDBOX, $sut->getEnvironment());
    }

    /**
     * @test
     * @small
     * @dataProvider environmentUrlAndVersionProvider
     */
    public function it_configures_the_http_client_with_the_environment_url_as_the_base_uri(
        ?string $environmentName,
        string $environmentUrl,
        string $environmentVersion
    ): void {
        $class = $this->getClass();

        /** @var Base $sut */
        $sut = new $class(self::TEST_TOKEN, $environmentName);

        $httpClient = $sut->getHttp();

        $expectedUri = sprintf("%s/api/%s/", $environmentUrl, $environmentVersion);

        $this->assertEquals($expectedUri, (string)$httpClient->getConfig('base_uri'));
    }

    /**
     * @test
     * @small
     */
    public function it_creates_the_http_client_with_authorization_header_based_on_the_provided_token(): void
    {
        $class = $this->getClass();

        /** @var Base $sut */
        $sut = new $class(self::TEST_TOKEN);

        $httpClient = $sut->getHttp();

        $authorizationHeader = $httpClient->getConfig('headers')['Authorization'];

        $expectedToken = sprintf("Bearer %s", self::TEST_TOKEN);

        $this->assertEquals($expectedToken, $authorizationHeader);
    }

    /**
     * @test
     * @small
     */
    public function it_creates_the_http_client_with_accept_header_as_application_json(): void
    {
        $class = $this->getClass();

        /** @var Base $sut */
        $sut = new $class(self::TEST_TOKEN);

        $httpClient = $sut->getHttp();

        $acceptHeader = $httpClient->getConfig('headers')['Accept'];

        $this->assertEquals('application/json', $acceptHeader);
    }

    /**
     * @test
     * @small
     * @dataProvider environmentProvider
     */
    public function it_can_get_the_environment(string $expectedEnvironmentName): void
    {
        $class = $this->getClass();

        /** @var Base $sut */
        $sut = new $class(self::TEST_TOKEN, $expectedEnvironmentName);

        $this->assertEquals($expectedEnvironmentName, $sut->getEnvironment());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_the_environment(): void
    {
        $class = $this->getClass();

        /** @var Base $sut */
        $sut = new $class(self::TEST_TOKEN, Environment::SANDBOX);

        $sut->setEnvironment(Environment::PRODUCTION);

        $this->assertEquals(Environment::PRODUCTION, $sut->getEnvironment());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_the_http_client(): void
    {
        $client = Mockery::mock(ClientInterface::class);

        $class = $this->getClass();

        /** @var Base $sut */
        $sut = new $class(self::TEST_TOKEN);

        $sut->setHttp($client);

        $this->assertSame($client, $sut->getHttp());
    }

    public function environmentUrlAndVersionProvider(): array
    {
        return [
            'providing null fills with SANDBOX' => [
                null,
                Endpoint::ENDPOINTS[Environment::SANDBOX],
                Endpoint::VERSIONS[Environment::SANDBOX],
            ],
            'providing SANDBOX fills with SANDBOX' => [
                Environment::SANDBOX,
                Endpoint::ENDPOINTS[Environment::SANDBOX],
                Endpoint::VERSIONS[Environment::SANDBOX],
            ],
            'providing PRODUCTION fills with PRODUCTION' => [
                Environment::PRODUCTION,
                Endpoint::ENDPOINTS[Environment::PRODUCTION],
                Endpoint::VERSIONS[Environment::PRODUCTION],
            ],
        ];
    }

    public function environmentProvider(): array
    {
        return [
            'null' => [Environment::SANDBOX],
            'SANDBOX' => [Environment::SANDBOX],
            'PRODUCTION' => [Environment::PRODUCTION],
        ];
    }
}
