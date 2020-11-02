<?php

namespace MamcoSy\Router;

class Route
{
    /**
     * Route path
     *
     * @var string
     */
    protected $path;

    /**
     * Route callback
     *
     * @var string|callable
     */
    protected $callback;

    /**
     * Route name
     *
     * @var string|null
     */
    protected $name;

    /**
     * Route matches parameters
     *
     * @var array
     */
    protected $matches;

    /**
     * Route matches parameters condition
     *
     * @var array
     */
    protected $params;

    /**
     * Route constructor
     *
     * @param string $path
     * @param string|callable $callback
     * @param string $name
     */
    public function __construct(string $path, $callback, string $name = null)
    {
        $this->path     = trim($path, '/');
        $this->callback = $callback;
        $this->name     = $name;
        $this->matches  = [];

    }

    /**
     * Match a route
     *
     * @param string $url
     * @return bool
     */
    public function match(string $url)
    {
        $url   = trim($url, '/');
        $path  = preg_replace_callback('#{([\w]+)}#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#";
        var_dump($regex);
        if (!preg_match($regex, $url, $this->matches)) {
            return false;
        }
        array_shift($this->matches);
        return true;
    }

    /**
     * Set condition for matches parameters
     *
     * @param string $param
     * @param string $regexCondition
     * @return self
     */
    public function with(string $param, string $regexCondition)
    {
        $this->params[$param] = str_replace('(', '(?:', $regexCondition);
        return $this;
    }

    /**
     * Adding condition in matches parameters
     *
     * @param array $matche
     * @return string
     */
    public function paramMatch(array $matche)
    {
        if (isset($this->params[$matche[1]])) {
            return '(' . $this->params[$matche[1]] . ')';
        }
        return '([^/]+)';
    }

    /**
     * Call a route
     *
     * @return mixed
     */
    public function call()
    {
        if (is_string($this->callback)) {
            list($controller, $method) = explode('@', $this->callback);
            var_dump($controller);
            return call_user_func_array([new $controller, $method], $this->matches);
        }
        return call_user_func_array($this->callback, $this->matches);
    }
}
