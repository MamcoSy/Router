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

    /**
     * Adding new route in route collection
     *
     * @param Route $route
     * @return void
     */
    public function add(Route $route)
    {
        if ($this->has($route->getName())) {
            throw new RouteAlreadyExistsException();
        }

        $this->routeCollection[$route->getName()] = $route;
    }

    /**
     * get a route by his name
     *
     * @param string $name
     * @return Route
     */
    public function get(string $name): Route
    {
        if ($this->has($name)) {
            return $this->routeCollection[$name];
        }
        throw new RouteNotFoundException();
    }

    /**
     * cheking if route exist in route colletion
     *
     * @param string $name
     * @return boolean
     */
    public function has(string $name): bool
    {
        return isset($this->routeCollection[$name]);
    }

    /**
     * Match route with a path
     *
     * @param string $path
     * @return mixed
     */
    public function match(string $path)
    {
        foreach ($this->routeCollection as $route) {
            if ($route->match($path)) {
                return $route->call();
            }
        }
        throw new RouteNotFoundException();

    }
}
