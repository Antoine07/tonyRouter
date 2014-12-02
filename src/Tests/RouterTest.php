<?php

namespace Tests;

use Services\Router\Router;
use Services\Router\Route;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests routing
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

    public function testSameRouteStorage()
    {
        $routes = Yaml::parse(__DIR__ . '/Fixtures/routes.yml');
        foreach ($routes as $route) {
            $this->router->addRoute(new Route($route));
        }

        $this->assertEquals(5, count($this->router));

        $route2 = Yaml::parse(__DIR__ . '/Fixtures/routes2.yml');

        $this->setExpectedException('RuntimeException', 'Cannot override route "Controllers\BlogController:index".');
        $this->router->addRoute(new Route($route2['BlogController_index']));
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

    public function testGetterControllerActionParams()
    {
        $route = Yaml::parse(__DIR__ . '/Fixtures/routes.yml');
        $this->router->addRoute(new Route($route['BlogController_show']));
        $this->router->addRoute(new Route($route['BlogController_index']));
        $this->router->addRoute(new Route($route['CategoryController_show']));
        
        $r = $this->router->getRoute('/php-tour-2015/2');
        $this->assertEquals('Controllers\BlogController', $r->getController());
        $this->assertEquals('show', $r->getAction());
        $this->assertEquals(['id' => 2], $r->getParams());
        
        $r = $this->router->getRoute('/vagrant-vm-tour/100');
        $this->assertEquals('Controllers\BlogController', $r->getController());
        $this->assertEquals('show', $r->getAction());
        $this->assertEquals(['id' => 100], $r->getParams());
        
        $r = $this->router->getRoute('/le-Tour-Symfony2/21');
        $this->assertEquals('Controllers\BlogController', $r->getController());
        $this->assertEquals('show', $r->getAction());
        $this->assertEquals(['id' => 21], $r->getParams());
        
        $r = $this->router->getRoute('/');
        $this->assertEquals('Controllers\BlogController', $r->getController());
        $this->assertEquals('index', $r->getAction());
        $this->assertEquals(NULL, $r->getParams());
        
        // category slug test
        $r = $this->router->getRoute('/cat/php/21/1');
        $this->assertEquals('Controllers\CategoryController', $r->getController());
        $this->assertEquals('show', $r->getAction());
        $this->assertEquals(['cat_id' => 21, 'user_id'=>1], $r->getParams());
    }

}
