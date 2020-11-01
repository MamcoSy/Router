<?php

namespace MamcoSy\Router;

class Route
{
    /**
     * Route name
     *
     * @var string
     */
    private $name;

    /**
     * Route path
     *
     * @var string
     */
    private $path;

    /**
     * callback
     *
     * @var array|callable
     */
    private $callback;

    /**
     * matches params
     *
     * @var array
     */
    private $matches = null;

    public function __construct(string $name, string $path, $callback)
    {
        $this->name     = $name;
        $this->path     = $path;
        $this->callback = $callback;
    }

    /**
     * Get route name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get route path
     *
     * @return  string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get callback
     *
     * @return  array|callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Get matches params
     *
     * @return  array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Matching curent route
     *
     * @param string $path
     * @return int|false
     */
    public function match(string $path)
    {
        $pattern = str_replace("/", "\/", $this->path);
        $pattern = sprintf("/^%s$/", $pattern);
        $pattern = preg_replace("/(\{\w+\})/", "(.+)", $pattern);
        return preg_match($pattern, $path, $this->matches);
    }

    /**
     * Call the route callback
     *
     * @return mixed
     */
    public function call()
    {
        if (!is_null($this->matches)) {
            array_shift($this->matches);
            if (is_array($this->callback)) {
                return call_user_func_array(
                    [new $this->callback[0], $this->callback[1]],
                    $this->matches
                );
            }
            return call_user_func_array($this->callback, $this->matches);
        }
        throw new RouteNotFoundException();
    }
}
