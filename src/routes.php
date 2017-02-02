<?php

use Zend\Diactoros\ServerRequestFactory;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Response;
use Slim\Views\PhpRenderer;

$request = ServerRequestFactory::fromGlobals(
	$_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$routerContainer = new RouterContainer();

$map = $routerContainer->getMap();

$view = new PhpRenderer(__DIR__ . '/../templates/');

$map->get('home', '/', function($requet, $response) use ($view) {
	
	return $view->render($response, 'home.phtml', [
		'test' => 'Slim PHP View funcionando.'
	]);
	
});


$matcher = $routerContainer->getMatcher();


$route = $matcher->match($request);


foreach ($route->attributes as $key => $value) {
	$request = $request->withAttribute($key, $value);
}


$callable = $route->handler;


/** @var Response $response */
$response = $callable($request, new Response());



echo $response->getBody();
