<?php

declare(strict_types=1);

namespace FondBot\Application;

use Zend\Diactoros\Response;
use League\Route\RouteCollection;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class RouteServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'request',
        'response',
        'emitter',
        'router',
    ];

    /**
     * Define routes.
     *
     * @param RouteCollection $routes
     *
     * @return void
     */
    abstract public function routes(RouteCollection $routes): void;

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \InvalidArgumentException
     */
    public function register(): void
    {
        $this->container->share('request', ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES));
        $this->container->share('response', Response::class);
        $this->container->share('emitter', SapiEmitter::class);

        $this->container->share('router', function () {
            $router = new RouteCollection($this->container);

            $this->routes($router);

            return $router;
        });
    }
}
