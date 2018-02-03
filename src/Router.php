<?php
namespace DevOp\Core\Router;

use DevOp\Core\Router\Route;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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

    public function getAll()
    {
        return $this->collection;
    }

    /**
     * 
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws Exceptions\RouteNotFoundException
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response)
    {

        if (!isset($this->collection[$request->getMethod()])) {
            throw new Exceptions\RouteNotFoundException();
        }

        $uri = $request->getUri()->getPath();
        foreach ($this->collection[$request->getMethod()] AS /* @var $route Route */ $route) {
            if (preg_match("#^{$route->getPattern()}+$#iu", $uri, $match)) {
                return $this->process($request, $response, $route);
            }
        }

        throw new Exceptions\RouteNotFoundException();
    }

    /**
     * 
     * @param Route $route
     * @return ResponseInterface
     * @throws Exceptions\RouteIsNotCallableException
     */
    public function process(RequestInterface $request, ResponseInterface $response, Route $route)
    {
        if (is_callable($route->getCallback())) {
            return call_user_func_array(/** @scrutinizer ignore-type */ $route->getCallback(), [$request, $response]);
        } else if (is_string($route->getCallback())) {
            if (strchr($route->getCallback(), ':')) {
                list($controller, $method) = explode(':', $route->getCallback());
            } else {
                $controller = $route->getCallback();
                $method = '__invoke';
            }
            return call_user_func_array([$controller, $method], [$request, $response]);
        } else if (is_array($route->getCallback())) {
            $controller = $route->getCallback()[0];
            $method= $route->getCallback()[1];
            $parameters = array_merge([$request, $response], array_slice($route->getCallback(), 2));
            call_user_func_array([$controller, $method], $parameters);
        }

        throw new Exceptions\RouteIsNotCallableException();
    }

    public function get($name, $pattern, $callback)
    {
        return $this->add($name, ['GET'], $pattern, $callback);
    }

    public function post($name, $pattern, $callback)
    {
        return $this->add($name, ['POST'], $pattern, $callback);
    }

    public function put($name, $pattern, $callback)
    {
        return $this->add($name, ['PUT'], $pattern, $callback);
    }

    public function delete($name, $pattern, $callback)
    {
        return $this->add($name, ['DELETE'], $pattern, $callback);
    }

    public function options($name, $pattern, $callback)
    {
        return $this->add($name, ['OPTIONS'], $pattern, $callback);
    }

    public function patch($name, $pattern, $callback)
    {
        return $this->add($name, ['PATCH'], $pattern, $callback);
    }

    public function head($name, $pattern, $callback)
    {
        return $this->add($name, ['HEAD'], $pattern, $callback);
    }

    public function any($name, $pattern, $callback)
    {
        return $this->add($name, ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'HEAD'], $pattern, $callback);
    }
}
