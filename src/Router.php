<?php

namespace MamcoSy\Router;

use MamcoSy\Router\Exceptions\RouteNotFoundException;
use MamcoSy\Router\Exceptions\RouterMethodNotFoundException;

class Router
{
    protected $url;
    protected $method;
    protected $routes;

    public function __construct(string $url, string $method)
    {
        $this->url    = trim($url);
        $this->method = $method;
    }

    public function get(string $path, $callback, string $name = null)
    {
        $route                 = new Route($path, $callback, $name);
        $this->routes['GET'][] = $route;
        return $route;
    }

    public function post(string $path, $callback, string $name)
    {
        $route                  = new Route($path, $callback, $name);
        $this->routes['POST'][] = $route;
        return $route;
    }

    public function any(string $path, $callback, string $name)
    {
        $route                  = new Route($path, $callback, $name);
        $this->routes['GET'][]  = $route;
        $this->routes['POST'][] = $route;
        return $route;
    }

    public function resolve()
    {
        if (!isset($this->routes[$this->method])) {
            throw new RouterMethodNotFoundException();
        }
        foreach ($this->routes[$this->method] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }
        throw new RouteNotFoundException();
    }
}
