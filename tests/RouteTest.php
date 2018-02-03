<?php
namespace DevOp\Core\Router\Test;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    public function testAddRoute()
    {
        $route = new \DevOp\Core\Router\Route('/', function() {
            
        });
        $this->assertInstanceOf('\DevOp\Core\Router\Route', $route);
    }
}
