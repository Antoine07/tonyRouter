<?php

namespace Services\Router;

/**
 * Description of Router
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
     * 
     * @param array $routes
     * @throws \RuntimeException
     */
    public function addRoute(Routable $route)
    {
        if ($this->routes->contains($route)) {
            throw new \RuntimeException(\sprintf('Cannot override route "%s".', $route->getController()));
        }

        $this->routes->attach($route);
    }

    /**
     * 
     * @param type $url
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

    public function count()
    {
        return count($this->routes);
    }

}
