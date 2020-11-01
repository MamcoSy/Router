<?php

namespace MamcoSy\Tests;

use MamcoSy\Router\Route;
use MamcoSy\Router\RouteAlreadyExistsException;
use MamcoSy\Router\RouteNotFoundException;
use MamcoSy\Router\Router;
use MamcoSy\Tests\Fixtures\TestController;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function test()
    {
        $router = new Router();
        $route  = new Route("home", "/", function () {
            return "salut";
        });

        $router->add($route);
        $this->assertCount(1, $router->getRouteCollection());
        $this->assertEquals($route, $router->get('home'));

    }

    public function testRouteAlreadyExists()
    {
        $router = new Router();
        $route1 = new Route("home", "/", function () {
            return "salut";
        });
        $route2 = new Route("home", "/", function () {
            return "salut";
        });

        $router->add($route1);

        $this->expectException(RouteAlreadyExistsException::class);
        $router->add($route2);

    }

    public function testIfRouteNotFound()
    {
        $router = new Router();
        $this->expectException(RouteNotFoundException::class);
        $router->get('dfdf');

    }

    public function testMatcheRoute()
    {
        $router = new Router();

        $route = new Route("blog", "/blog/{id}/{slug}", function (int $id, string $slug) {
            return "blog";
        });

        $route2 = new Route("home", "/", [TestController::class, 'index']);

        $router->add($route);
        $router->add($route2);

        $this->assertEquals($router->match('/'), 'test success');
        $this->assertEquals($router->match('/blog/45/mon-alticle'), 'blog');

    }
}
