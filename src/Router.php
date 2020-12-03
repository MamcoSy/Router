<?php

namespace MamcoSy\Router;

use MamcoSy\Router\Exceptions\RouteNotFoundException;
use MamcoSy\Router\Exceptions\RouterMethodNotFoundException;

class Router
{
    /**
     * Request url
     *
     * @var string
     */

    protected $url;
    /**
     * Request method
     *
     * @var string
     */
    protected $method;

    /**
     * Collection of routes
     *
     * @var Route[]
     */
    protected $routeCollection;

    /**
     * Router constructor
     *
     * @param string $url
     * @param string $method
     */
    public function __construct( string $url, string $method )
    {
        $this->url    = trim( $url );
        $this->method = $method;
    }

    /**
     * Router get Method
     *
     * @param  string          $path
     * @param  string|callable $callback
     * @param  string          $name
     * @return Route
     */
    public function get( string $path, $callback, string $name = null )
    {
        $route                          = new Route( $path, $callback, $name );
        $this->routeCollection['GET'][] = $route;
        return $route;
    }

    /**
     * Router post Method
     *
     * @param  string          $path
     * @param  string|callable $callback
     * @param  string          $name
     * @return Route
     */
    public function post( string $path, $callback, string $name )
    {
        $route                           = new Route( $path, $callback, $name );
        $this->routeCollection['POST'][] = $route;
        return $route;
    }

    /**
     * Router post and get Method
     *
     * @param  string          $path
     * @param  string|callable $callback
     * @param  string          $name
     * @return Route
     */
    public function any( string $path, $callback, string $name )
    {
        $route                           = new Route( $path, $callback, $name );
        $this->routeCollection['GET'][]  = $route;
        $this->routeCollection['POST'][] = $route;
        return $route;
    }

    /**
     * find a matched route and call it
     *
     * @return mixed
     */
    public function resolve()
    {

        if ( !isset( $this->routeCollection[$this->method] ) ) {
            throw new RouterMethodNotFoundException();
        }

        foreach ( $this->routeCollection[$this->method] as $route ) {

            if ( $route->match( $this->url ) ) {
                return $route->call();
            }

        }

        throw new RouteNotFoundException();
    }

}
