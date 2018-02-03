<?php
include_once './vendor/autoload.php';

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

$router = new \DevOp\Core\Router\Router();

$router->add('homepage', ['GET', 'POST'], '/', function(RequestInterface $request, ResponseInterface $response) {
    $body = $response->withStatus(404);
    $body->getBody()->write('Error 404');
    echo $body->getBody();
});
$router->add('anything_else', ['GET'], '/[page:\w]', function() {
    echo "ANYTHING ELSE" . PHP_EOL;
});

$router->any('anything_else', '/users/[action:\w]/[id:\d+]', function() {
    echo "ANYTHING ELSE" . PHP_EOL;
});
 
function getCurrentUrl()
{
    $url = '';
    
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'On') {
        $url .= 'https://';
    } else if (isset($_SERVER['REQUEST_SCHEME'])) {
        $url .=  $_SERVER['REQUEST_SCHEME'];
    }
    
    $url .= "://";
    
    if (isset($_SERVER['HTTP_HOST'])) {
        $url .= $_SERVER['HTTP_HOST'];
    } else if (isset($_SERVER['SERVER_NAME'])) {
        $url .= $_SERVER['SERVER_NAME'];
    } else {
        $url .= 'localhost';
    }
    
    if (isset($_SERVER['SERVER_PORT']) && !in_array($_SERVER['SERVER_PORT'], [80, 443])) {
        $url .= ':' . $_SERVER['SERVER_PORT'];
    }
    
    if (isset($_SERVER['BASE'])) {
        $url .= str_replace($_SERVER['BASE'], '', $_SERVER['REQUEST_URI']);
    } else {
        $url .= $_SERVER['REQUEST_URI'];
    }
    
    return $url;
}

$uri = (new \DevOp\Core\Http\Factory\UriFactory())->createUri(getCurrentUrl());
$request = (new DevOp\Core\Http\Factory\RequestFactory())->createRequest('GET', $uri);
$response = (new DevOp\Core\Http\Factory\ResponseFactory())->createResponse(200);
$router->dispatch($request, $response);
