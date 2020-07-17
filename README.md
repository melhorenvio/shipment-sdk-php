# Shipment SDK - Serviço de cotações do Melhor Envio

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melhorenvio/shipment-sdk.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/shipment-sdk)
[![Build Status](https://img.shields.io/travis/melhorenvio/shipment-sdk/master.svg?style=flat-square)](https://travis-ci.org/melhorenvio/shipment-sdk)
[![Quality Score](https://img.shields.io/scrutinizer/g/melhorenvio/shipment-sdk.svg?style=flat-square)](https://scrutinizer-ci.com/g/melhorenvio/shipment-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/melhorenvio/shipment-sdk.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/shipment-sdk)

Agora ficou mais fácil ter o serviço de cotações do Melhor Envio no seu projeto de e-commerce.

## Índice

* [Instalação](##Instalação)
* [Cofiguração Inicial](##Configuração-Inicial)
* [Exemplos de uso](##Pacotes)
    * [Pacotes](##Pacotes)
        * [Criação da calculadora](###Criando-a-instância-calculadora)
        * [Montando o payload da calculadora](###Montando-o-payload-da-calculadora)
            * [Adicionando CEPs de origem e destino](####Adicionando-CEPs-de-origem-e-destino)
            * [Adicionando serviços adicionais](####Adicionando-serviços-adicionais)
            * [Adicionando os pacotes para cotação](####Adicionando-os-pacotes-para-cotação)
            * [Adicionando os serviços das transportadoras](####Adicionando-os-serviços-das-transportadoras)
            * [Retornando a cotação](####Retornando-as-informações-da-cotação)
    * [Produtos](##Produtos)
        * []


## Dependências

### require 
* PHP >= 7.1
* Ext-json = *
* Guzzlehttp/guzzle >= 6.5

### require-dev
* phpunit/phpunit >= 5


## Instalação

Você pode instalar o pacote via composer, rodando o seguinte comando:

```bash
composer require melhorenvio/shipment-sdk
```

## Configuração inicial

A instância criada de Shipment permite que você passe como parâmetros o seu token e o ambiente que você trabalhará, assim terá a autenticação pronta. 

Lembrando que só será válido, se a criação do token pertencer ao mesmo ambiente passado como parâmetro. 

Se você ainda não possui token, você pode criá-lo [aqui](%https://melhorenvio.com.br/painel/gerenciar/tokens%).

``` php
require "vendor/autoload.php";

use MelhorEnvio\Shipment;
use MelhorEnvio\Resources\Shipment\Package;
use MelhorEnvio\Enums\Service;
use MelhorEnvio\Enums\Environment;

// Create Shipment Instance
$shipment = new Shipment('your-token', Environment::PRODUCTION);
```

## Pacotes

Este exemplo consiste na cotação para pacotes.

### Criando a instância calculadora

Neste exemplo você criará uma instância para calculadora no seu código.

```php
// Create Calculator Instance
    $calculator = $shipment->calculator();
```

### Montando o payload da calculadora

#### Adicionando CEPs de origem e destino

Nesta parte você deve definir os CEPs de origem e destino respectivamente. 

``` php
//Builds calculator payload
$calculator->postalCode('01010010', '20271130');
```
#### Adicionando serviços adicionais

Aqui você pode configurar alguns serviços adicionais na sua cotação, são eles:
* Mão própria
* Aviso de recebimento
* Coleta

Lembrando que a adição desses serviços podem gerar acréscimos no preço na hora da cotação.

 ```php
    $calculator->setOwnHand();
    $calculator->setReceipt(false);
    $calculator->setCollect(false);
 ```

#### Adicionando os pacotes para cotação

Nesta parte, você define os pacotes que servirão para sua cotação, as informações que devem ser passadas como parâmetro são as seguintes:
* Altura
* Largura
* Comprimento
* Peso
* Valor segurado

Lembrando que o valor segurado por padrão deve ser o valor do produto.

```php
 $calculator->addPackages(
        new Package(12, 4, 17, 0.1, 6.0),
        new Package(12, 4, 17, 0.1, 6.0),
        new Package(12, 4, 17, 0.1, 6.0),
        new Package(12, 4, 17, 0.1, 6.0)
    );
```

#### Adicionando os serviços das transportadoras

Aqui serão escolhidos os serviços das transportadoras que você deseja utilizar. Hoje, no Melhor Envio, estão disponíveis:
* Correios
* Jadlog
* Via Brasil
* Azul Cargo 

```php
$calculator->addServices(
        Service::CORREIOS_PAC, Service::CORREIOS_SEDEX Service::JADLOG_PACKAGE, Service::JADLOG_COM, Service::AZULCARGO_AMANHA
    );
```

#### Retornando as informações da cotação

Aqui você retornará as informações do payload montado.

```php
$quotations = $calculator->calculate();
print_r($quotations);
exit;
```



## Produtos

Este exemplo consiste na cotação para produtos

### Criando a instância da calculadora 

Neste exemplo você 
```php

```











### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email pedro.barros@melhorenvio.com instead of using the issue tracker.

## Credits

- [Pedro Barros](https://github.com/melhorenvio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).