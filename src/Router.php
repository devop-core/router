<?php
namespace DevOp\Core\Router;

use DevOp\Core\Router\Route;
use Psr\Http\Message\RequestInterface;

class Router
{

    /**
     * @var array
     */
    private $collection;

    /**
     * @param string $name
     * @param array $methods
     * @param string $pattern
     * @param object|callback|array $callback
     * @return $this
     */
    public function add($name, array $methods, $pattern, $callback)
    {
        /* @var $route Route */
        $route = new Route($pattern, $callback);

        foreach ($methods AS $method) {
            if (!isset($this->collection[$method])) {
                $this->collection[$method] = [];
            }
            $this->collection[$method][$name] = $route;
        }

        return $this;
    }

    public function match(RequestInterface $request)
    {
        if (isset($this->collection[$request->getMethod()])) {
            foreach ($this->collection[$request->getMethod()] AS /* @var $route Route */ $route) {
                $match = null;
                if (preg_match_all($route->getRegEx(), $request->getRequestTarget(), $match)) {
                    var_dump($match);
                }
            }
        }
    }

    public function getAll()
    {
        return $this->collection;
    }

    public function dispatch($method, $uri)
    {

        if (!isset($this->collection[$method])) {
            die('$)$');
        }

        foreach ($this->collection[$method] AS /* @var $route Route */ $route) {
            if (preg_match("#^{$route->getPattern()}+$#iu", $uri)) {
                var_dump($route);
            }
        }
    }

    public function get($name, $pattern, $callback)
    {
        return $this->add($name, 'GET', $pattern, $callback);
    }

    public function post($name, $pattern, $callback)
    {
        return $this->add($name, 'POST', $pattern, $callback);
    }

    public function put($name, $pattern, $callback)
    {
        return $this->add($name, 'PUT', $pattern, $callback);
    }

    public function delete($name, $pattern, $callback)
    {
        return $this->add($name, 'DELETE', $pattern, $callback);
    }

    public function options($name, $pattern, $callback)
    {
        return $this->add($name, 'OPTIONS', $pattern, $callback);
    }

    public function patch($name, $pattern, $callback)
    {
        return $this->add($name, 'PATCH', $pattern, $callback);
    }

    public function head($name, $pattern, $callback)
    {
        return $this->add($name, 'HEAD', $pattern, $callback);
    }

    public function any($name, $pattern, $callback)
    {
        return $this->add($name, ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'HEAD'], $pattern, $callback);
    }
}
