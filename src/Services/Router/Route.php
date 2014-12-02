<?php

namespace Services\Router;

class Route implements Routable
{

    protected $controller;
    protected $action;
    protected $params = NULL;
    protected $route;

    public function __construct(array $route)
    {
        $this->route = $route;

        if (empty($route['connect'])) {
            throw new \RuntimeException('Bad syntax connect.');
        }

        $this->setConnect($route['connect']);
    }

    public function setConnect($connect)
    {
        $connect = explode(':', $connect);
        if (count($connect) != 2) {
            throw new \RuntimeException('Bad syntax connect.');
        }
        $this->controller = $connect[0];
        $this->action = $connect[1];
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
            $param = trim($p);
            $this->params[] = $m[$p];
        }
    }

}
