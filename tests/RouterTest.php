<?php
namespace DevOp\Core\Router\Test;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var Router
     */
    private $router;

    public function setUp()
    {
        $this->router = new \DevOp\Core\Router\Router();
    }

    public function testAddRoute()
    {
        $router = $this->router->add('homepage', ['GET'], '/', function() {
            
        });
        $this->assertInstanceOf('\DevOp\Core\Router\Router', $router);
    }
}
