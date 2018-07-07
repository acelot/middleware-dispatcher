# Middleware Dispatcher

[![packagist](https://img.shields.io/packagist/v/acelot/middleware-dispatcher.svg?style=flat)](https://packagist.org/packages/acelot/middleware-dispatcher)
![deps](https://img.shields.io/badge/dependencies-zero-blue.svg?style=flat)
![license](https://img.shields.io/github/license/acelot/middleware-dispatcher.svg?style=flat)

[PSR-15](https://www.php-fig.org/psr/psr-15/) compliant middleware dispatcher.

## Install

```bash
composer require acelot/middleware-dispatcher
```

Also you need to install some [PSR-11](https://www.php-fig.org/psr/psr-11/) complaint DI container like `acelot/resolver` or `php-di/php-di`.

## Example

**`entrypoint.php`**
```php
$resolver = new Psr11CompliantDiContainer();

$dispatcher = new MiddlewareDispatcher($resolver, [
    FirstMiddleware::class,
    SecondMiddleware::class,
    ThirdMiddleware::class,
    ...,
    LastMiddleware::class
]);

$response = $dispatcher->handle($serverRequest);
```

**`FirstMiddleware.php`**
```php
class FirstMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Request handler
    }
}
```
