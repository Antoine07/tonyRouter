<?php

namespace Services\Router;

/**
 * Router
 *
 * @author antoine
 */
class Router implements \Countable
{

    protected $routes;

    public function __construct()
    {
        $this->routes = new \SplObjectStorage;
    }
    
    /**
     * <pre> pass a route string and return a object Route</pre>
     * 
     * @param string $url
     * @return  \RuntimeException | object Route
     */
    public function getRoute($url)
    {
        foreach ($this->routes as $route) {
            if ($route->isMatch($url)) {
                return $route;
            }
        }

        throw new \RuntimeException("bad route exception");
    }
    
    /**
     * 
     * @param array $routes
     * @throws \RuntimeException
     */
    public function addRoute(Routable $route)
    {
        if ($this->isSameRoute($route->getName())) {
            throw new \RuntimeException(\sprintf('Cannot override route "%s".', $route->getName()));
        }

        $this->routes->attach($route);
    }

    /**
     * count a number of routes in storage
     * 
     * @return int
     */
    public function count()
    {
        return count($this->routes);
    }
    
    protected function isSameRoute($name)
    {
        foreach ($this->routes as $route) {
            if ($route->getName()==$name) {
                return true;
            }
        }
        return false;
    }

}
