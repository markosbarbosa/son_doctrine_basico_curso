<?php

use Zend\Diactoros\ServerRequestFactory;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Response;

$request = ServerRequestFactory::fromGlobals(
	$_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$routerContainer = new RouterContainer();

$map = $routerContainer->getMap();

$map->get('home', '/home', function($requet, $response) {
	
	$response->getBody()->write("Hello World");
	return $response;
});


$matcher = $routerContainer->getMatcher();

$route = $matcher->match($request);

foreach ($route->attributes as $key => $value) {
	$request = $request->withoutAttribute($key, $value);
}

$callable = $route->handler;

/** @var Response $response */
$response = $callable($request, new Response());

echo $response->getBody();
