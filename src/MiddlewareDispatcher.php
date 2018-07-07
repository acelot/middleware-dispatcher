<?php declare(strict_types=1);

namespace Acelot\MiddlewareDispatcher;

use Acelot\MiddlewareDispatcher\Exception\UnterminatedStackException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcher implements RequestHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $resolver;

    /**
     * @var string[]
     */
    private $stack;

    /**
     * Dispatcher constructor.
     *
     * @param ContainerInterface $resolver Dependency Injection Container with auto-wiring.
     *                                     Like `acelot/resolver` or `php-di/php-di`.
     * @param string[]           $stack    An array of middleware class names.
     *                                     Each middleware MUST implement `MiddlewareInterface`.
     */
    public function __construct(ContainerInterface $resolver, array $stack)
    {
        $this->resolver = $resolver;
        $this->stack = $stack;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws UnterminatedStackException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->stack)) {
            throw new UnterminatedStackException('The stack ran out. No middleware returned the response.');
        }

        return $this->resolve($this->stack[0])->process($request, $this->newHandler());
    }

    /**
     * @param string $fqcn Fully Qualified Class Name
     *
     * @return MiddlewareInterface
     */
    protected function resolve(string $fqcn): MiddlewareInterface
    {
        return $this->resolver->get($fqcn);
    }

    /**
     * @return MiddlewareDispatcher
     */
    private function newHandler(): MiddlewareDispatcher
    {
        return new MiddlewareDispatcher($this->resolver, array_slice($this->stack, 1));
    }
}
