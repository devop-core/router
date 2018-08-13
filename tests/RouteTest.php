<?php
namespace DevOp\Core\Router\Test;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \DevOp\Core\Router\Route
     */
    private $route;
    
    public function setUp()
    {
        $this->route = new \DevOp\Core\Router\Route('/', 'handler01');
    }
    
    public function testGetMethods()
    {
        $this->assertEquals('/', $this->route->getPattern());
        $this->assertEquals('handler01', $this->route->getCallback());
    }
    
    public function testAddRoute()
    {
        $route = new \DevOp\Core\Router\Route('/', 'SomeController@SomeMethod');
        $this->assertInstanceOf('\DevOp\Core\Router\Route', $route);
    }
}
