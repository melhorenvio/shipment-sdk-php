# Shipment SDK - Serviço de cotações do Melhor Envio

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melhorenvio/shipment-sdk.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/shipment-sdk)
[![Build Status](https://img.shields.io/travis/melhorenvio/shipment-sdk/master.svg?style=flat-square)](https://travis-ci.org/melhorenvio/shipment-sdk)
[![Quality Score](https://img.shields.io/scrutinizer/g/melhorenvio/shipment-sdk.svg?style=flat-square)](https://scrutinizer-ci.com/g/melhorenvio/shipment-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/melhorenvio/shipment-sdk.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/shipment-sdk)

Agora ficou mais fácil ter o serviço de cotações do Melhor Envio no seu projeto de e-commerce.

## Índice

* [Instalação](##Instalação)
* [Cofiguração Inicial](##Configuração-Inicial)
* [Exemplos de uso](##Criando-a-instância-calculadora)
    * [Criação da calculadora](###Criando-a-instância-calculadora)
    * [Montando o payload da calculadora](###Montando-o-payload-da-calculadora)
    * [Adicionando CEPs de origem e destino](####Adicionando-CEPs-de-origem-e-destino)
    * [Adicionando serviços adicionais](####Adicionando-serviços-adicionais)
    * [Pacotes](###Pacotes)
        * [Adicionando os pacotes para cotação](####Adicionando-os-pacotes-para-cotação)
    * [Produtos](###Produtos)
        * [Adicionando os produtos para cotação](###Adicionando-os-produtos-para-cotação)
    * [Adicionando os serviços das transportadoras](####Adicionando-os-serviços-das-transportadoras)
    * [Retornando a cotação](####Retornando-as-informações-da-cotação)


## Dependências

### require 
* PHP >= 5.6
* Ext-json = *
* Guzzlehttp/guzzle >= 6.5

### require-dev
* phpunit/phpunit >= 5


## Instalação

Você pode instalar o pacote via composer, rodando o seguinte comando:

```bash
composer require melhorenvio/shipment-sdk-php
```

## Configuração inicial

A instância criada de Shipment permite que você passe como parâmetros o seu token e o ambiente que você trabalhará, assim terá a autenticação pronta. 

Lembrando que só será válido, se a criação do token pertencer ao mesmo ambiente passado como parâmetro. 

Se você ainda não possui token, você pode criá-lo [aqui](%https://melhorenvio.com.br/painel/gerenciar/tokens%).

```php
require "vendor/autoload.php";

use MelhorEnvio\Shipment;
use MelhorEnvio\Resources\Shipment\Package;
use MelhorEnvio\Enums\Service;
use MelhorEnvio\Enums\Environment;

// Create Shipment Instance
$shipment = new Shipment('your-token', Environment::PRODUCTION);
```

## Criando a instância calculadora

Neste exemplo você criará uma instância para calculadora no seu código.

```php
// Create Calculator Instance
    $calculator = $shipment->calculator();
```

## Montando o payload da calculadora

### Adicionando CEPs de origem e destino

Nesta parte você deve definir os CEPs de origem e destino respectivamente. 

```php
//Builds calculator payload
$calculator->postalCode('01010010', '20271130');
```
### Adicionando serviços adicionais

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

### Pacotes

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

### Produtos

#### Adicionando os produtos para cotação

Nesta parte, você define os produtos que servião para sua cotaçãom as informações que devem ser passadas como parâmetro são as seguintes:
* Altura
* Largura
* Comprimento
* Peso
* Valor segurado
* Quantidade

Lembrando que o valor segurado por padrão deve ser o valor do produto.

```php
$calculator->addProducts(
        new Product(uniqid(), 40, 30, 50, 10.00, 100.0, 1),
        new Product(uniqid(), 5, 1, 10, 0.1, 50.0, 1)
    );
```

**É importante ressaltar que sejam feitas as montagens dos payloads de PACOTES e PRODUTOS em arquivos separados. Um para cada responsabilidade, como segue no projeto.**

### Adicionando os serviços das transportadoras

Aqui serão escolhidos os serviços das transportadoras que você deseja utilizar. Hoje, no Melhor Envio, estão disponíveis:
* Correios
* Jadlog
* Via Brasil
* Azul Cargo 

```php
$calculator->addServices(
        Service::CORREIOS_PAC, 
        Service::CORREIOS_SEDEX,
        Service::JADLOG_PACKAGE, 
        Service::JADLOG_COM, 
        Service::AZULCARGO_AMANHA
    );
```

### Retornando as informações da cotação

Aqui você retornará as informações do payload montado.

```php
$quotations = $calculator->calculate();
print_r($quotations);
exit;
```

### Mais exemplos

[Aqui você pode acessar mais exemplos de implementação](/examples)

### Testes

``` bash
composer test
```

### Changelog

Consulte [CHANGELOG](CHANGELOG.md) para mais informações de alterações recentes.

## Contribuindo

Consulte [CONTRIBUTING](CONTRIBUTING.md) para mais detalhes.

### Segurança

Se você descobrir algum problema de segurança, por favor, envie um e-mail para tecnologia@melhorenvio.com, ao invés de usar um 'issue tracker'.

## Créditos

- [Rodrigo Silveira](https://github.com/melhorenvio)
- [Marçal Pizzi](https://github.com/melhorenvio)

## Licença

MIT License (MIT). Consulte [Arquivo de lincença](LICENSE.md) para mais informações.