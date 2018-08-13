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

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->collection;
    }

    /**
     * @param RequestInterface $request
     * @return Route
     * @throws Exceptions\RouteNotFoundException
     */
    public function dispatch(RequestInterface $request)
    {

        if (!isset($this->collection[$request->getMethod()])) {
            throw new Exceptions\RouteNotFoundException();
        }

        $uri = $request->getUri()->getPath();

        foreach ($this->collection[$request->getMethod()] AS /* @var $route Route */ $route) {
            if (preg_match("#^{$route->getRegEx()}+$#iu", $uri, $match)) {
                return $this->process($route, $match);
            }
        }

        throw new Exceptions\RouteNotFoundException();
    }

    /**
     * @param Route $route
     * @param array $match
     * @return Route
     * @throws Exceptions\RouteIsNotCallableException
     */
    public function process(Route $route, $match)
    {

        $arguments = [];
        foreach ($route->getParameters() AS $param) {
            if (isset($match[$param])) {
                $arguments[$param] = $match[$param];
            }
        }

        $route->setValues($arguments);

        if (is_callable($route->getCallback(), true)) {
            return $route;
        }

        throw new Exceptions\RouteIsNotCallableException;
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
     * @return self
     */
    public function get($name, $pattern, $callback)
    {
        return $this->add($name, ['GET'], $pattern, $callback);
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
     * @return self
     */
    public function post($name, $pattern, $callback)
    {
        return $this->add($name, ['POST'], $pattern, $callback);
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
     * @return self
     */
    public function put($name, $pattern, $callback)
    {
        return $this->add($name, ['PUT'], $pattern, $callback);
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
     * @return self
     */
    public function delete($name, $pattern, $callback)
    {
        return $this->add($name, ['DELETE'], $pattern, $callback);
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
     * @return self
     */
    public function options($name, $pattern, $callback)
    {
        return $this->add($name, ['OPTIONS'], $pattern, $callback);
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
     * @return self
     */
    public function patch($name, $pattern, $callback)
    {
        return $this->add($name, ['PATCH'], $pattern, $callback);
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
     * @return self
     */
    public function head($name, $pattern, $callback)
    {
        return $this->add($name, ['HEAD'], $pattern, $callback);
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param mixed $callback
     * @return self
     */
    public function any($name, $pattern, $callback)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'HEAD'];
        return $this->add($name, $methods, $pattern, $callback);
    }
}
