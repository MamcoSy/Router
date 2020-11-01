<?php

namespace MamcoSy\Router;

class Router
{

    /**
     * Collection of routes
     *
     * @var Route[]
     */
    public $routeCollection = [];

    public function getRouteCollection()
    {
        return $this->routeCollection;
    }

    public function add(Route $route)
    {
        if ($this->has($route->getName())) {
            throw new RouteAlreadyExistsException();
        }

        $this->routeCollection[$route->getName()] = $route;
    }

    public function get(string $name): Route
    {
        if ($this->has($name)) {
            return $this->routeCollection[$name];
        }
        throw new RouteNotFoundException();
    }

    public function has(string $name): bool
    {
        return isset($this->routeCollection[$name]);
    }

    public function match($path)
    {
        foreach ($this->routeCollection as $route) {
            if ($route->match($path)) {
                return $route->call();
            }
        }
        throw new RouteNotFoundException();

    }
}
