<?php

namespace Services\Router;

class Route implements Routable
{
    /**
     *
     * @var string
     */
    protected $controller;
    
    /**
     *
     * @var string
     */
    protected $action;
    
    /**
     *
     * @var NULL | array
     */
    protected $params = NULL;
    
    /**
     *
     * @var Object Routable
     */
    protected $route;
    
    /**
     * name of route
     * 
     * @var string
     */
    protected $name;

    public function __construct(array $route)
    {
        $this->route = $route;

        $connect = $route['connect'];

        if (empty($connect)) {
            throw new \RuntimeException('Bad syntax connect.');
        }

        $this->name = $connect;

        $this->setConnect($connect);
    }

    /**
     * Setter of paramaters route
     * 
     * @param array $m
     * @return NULL | array
     */
    public function setParams($m)
    {
        if (empty($this->route['params'])) {
            return;
        }
        
        $params = explode(',', $this->route['params']);
        foreach ($params as $p) {
            $p = trim($p);
            $this->params[$p] = $m[$p];
        }
    }

    /**
     * 
     *  two string separated by : to connect by order controller and action
     * 
     * @param array $connect
     * @throws \RuntimeException
     */
    public function setConnect($connect)
    {
        $c = explode(':', $connect);
        if (count($c) != 2) {
            throw new \RuntimeException('Bad syntax connect.');
        }

        list($this->controller, $this->action) = $c;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     *  <pre>Check url with pattern route </pre>
     * 
     * @param string $url
     * @return boolean
     */
    public function isMatch($url)
    {
        if (preg_match('/^' . $this->route['pattern'] . '$/', $url, $m)) {
            $this->setParams($m);
            return true;
        } else {
            return false;
        }
    }

}
