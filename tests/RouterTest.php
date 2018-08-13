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
        $router = $this->router->add('homepage', ['GET'], '/', 'handler01');
        $this->assertInstanceOf('\DevOp\Core\Router\Router', $router);
    }
    
    public function testGetMethod()
    {
        $router = $this->router->get('route01', '/', 'handler01');
        $this->assertInstanceOf('\DevOp\Core\Router\Router', $router);
    }
    
    public function testPostMethod()
    {
        $router = $this->router->get('route02', '/', 'handler02');
        $this->assertInstanceOf('\DevOp\Core\Router\Router', $router);
    }
    
    public function testOptionsMethod()
    {
        $router = $this->router->get('route03', '/', 'handler03');
        $this->assertInstanceOf('\DevOp\Core\Router\Router', $router);
    }
    
    public function testHeadMethod()
    {
        $router = $this->router->get('route04', '/', 'handler04');
        $this->assertInstanceOf('\DevOp\Core\Router\Router', $router);
    }
}
