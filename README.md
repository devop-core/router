# Router library

## Description

> This library is just proof of concept. > We do **NOT** recommended the use of production environment.

Provide router implementation

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Package is available on [Packagist](link-packagist), you can install it using [Composer](http://getcomposer.org).

``` bash
composer require devop-core/router
```

## Usage

``` php
<?php
include_once '../vendor/autoload.php';

$router = new DevOp\Core\Router\Router();
$router->get('page1', '/page1/{name:\w+}/{id}', function($request, $response){
    $response->getBody()->write('Hello world!');
    return $response;
});

$uri = (new DevOp\Core\Http\UriFactory())->createUri('/page1/devop/1');
$request = (new \DevOp\Core\Http\ServerRequestFactory())->createServerRequest('GET', $uri);

try {
    /* @var $route \DevOp\Core\Router\Route */
    $route = $router->dispatch($request);
} catch (\DevOp\Core\Router\Exceptions\RouteNotFoundException $ex) {
    var_dump($ex);
} catch (DevOp\Core\Router\Exceptions\RouteIsNotCallableException $ex) {
    var_dump($ex);
}

var_dump($route);
```

## Change log

Please see [CHANGELOG](.github/CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/devop-core/router.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/devop-core/router/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/devop-core/router.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/devop-core/router.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/devop-core/router.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/devop-core/router
[link-travis]: https://travis-ci.org/devop-core/router
[link-scrutinizer]: https://scrutinizer-ci.com/g/devop-core/router/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/devop-core/router
[link-downloads]: https://packagist.org/packages/devop-core/router
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
