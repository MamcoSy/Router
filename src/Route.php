<?php

namespace MamcoSy\Router;

class Route
{
    protected $path;
    protected $callback;
    protected $name;
    protected $matches;
    protected $params;

    public function __construct(string $path, $callback, string $name = null)
    {
        $this->path     = trim($path, '/');
        $this->callback = $callback;
        $this->name     = $name;
        $this->matches  = [];

    }

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

    public function with(string $param, string $regexCondition)
    {
        $this->params[$param] = str_replace('(', '(?:', $regexCondition);
        return $this;
    }

    public function paramMatch($matche)
    {
        if (isset($this->params[$matche[1]])) {
            return '(' . $this->params[$matche[1]] . ')';
        }
        return '([^/]+)';
    }

    public function call()
    {
        return call_user_func_array($this->callback, $this->matches);
    }
}
