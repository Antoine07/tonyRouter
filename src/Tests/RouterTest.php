<?php

namespace Tests;

use Services\Router\Router;
use Services\Router\Route;
use Symfony\Component\Yaml\Yaml;

/**
 * Description of RouterTest
 *
 * @author antoine
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    protected $router;
    protected $urls;

    public function setUp()
    {
        $this->router = new Router;
        $this->urls = Yaml::parse(__DIR__ . '/Fixtures/urls.yml');
    }

    public function assertPreConditions()
    {
        $this->assertEquals(0, count($this->router));
    }

    public function testAddRoutes()
    {
        $routes = Yaml::parse(__DIR__ . '/Fixtures/routes.yml');

        foreach ($routes as $route) {
            $this->router->addRoute(new Route($route));
        }
        $this->assertEquals(5, count($this->router));
    }

    public function testBadConnectRouteYml()
    {
        $routes = Yaml::parse(__DIR__ . '/Fixtures/bad.yml');
        $this->setExpectedException('RuntimeException', 'Bad syntax connect');
        $this->router->addRoute(new Route($routes['BlogController_index']));
    }

    public function testNoParamsReturnNull()
    {
        $routes = Yaml::parse(__DIR__ . '/Fixtures/noparam.yml');
        $this->router->addRoute(new Route($routes['BlogController_index']));
        $r = $this->router->getRoute('/');
        $this->assertEquals($r->getParams(), NULL);
    }

}
