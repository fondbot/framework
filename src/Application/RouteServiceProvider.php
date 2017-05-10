<?php

declare(strict_types=1);

namespace FondBot\Application;

use Whoops\Run;
use Zend\Diactoros\Response;
use League\Route\RouteCollection;
use Whoops\Handler\PrettyPageHandler;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use League\Container\ServiceProvider\AbstractServiceProvider;

class RouteServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'request',
        'response',
        'emitter',
        'router',
    ];

    private $prefix;

    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();

        $this->getContainer()->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER,
                $_GET,
                $_POST,
                $_COOKIE,
                $_FILES
            );
        });
        $this->getContainer()->share('response', Response::class);
        $this->getContainer()->share('emitter', SapiEmitter::class);

        $this->getContainer()->share('router', function () {
            $router = new RouteCollection($this->getContainer());

            $controller = new Controller($this->getContainer()->get(Kernel::class));

            $router->map('GET', $this->buildPath('/'), [$controller, 'index']);
            $router->map('GET', $this->buildPath('/channels/{name}'), [$controller, 'webhook']);

            return $router;
        });
    }

    private function buildPath(string $path): string
    {
        if ($this->prefix !== null) {
            return $this->prefix.'/'.$path;
        }

        return $path;
    }
}
