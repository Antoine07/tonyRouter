<?php

namespace Services\Router;

class Route implements Routable
{

    protected $controller;
    protected $action;
    protected $params = NULL;
    protected $route;
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

    public function isMatch($url)
    {
        if (preg_match('/^' . $this->route['pattern'] . '$/', $url, $m)) {
            $this->setParams($m);
            return true;
        } else {
            return false;
        }
    }

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

}
