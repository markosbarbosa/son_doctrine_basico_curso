<?php

use Zend\Diactoros\ServerRequestFactory;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Response;
use Slim\Views\PhpRenderer;
use App\Entity\Category;

$request = ServerRequestFactory::fromGlobals(
	$_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$routerContainer = new RouterContainer();

$map = $routerContainer->getMap();

$view = new PhpRenderer(__DIR__ . '/../templates/');


$entityManager = getEntityManager();


$map->get('home', '/', function($requet, $response) use ($view) {
	
	return $view->render($response, 'home.phtml', [
		'test' => 'Slim PHP View funcionando.'
	]);
	
});


$map->get('categories.list', '/categories', function($requet, $response) use ($view, $entityManager) {
	
	$repository = $entityManager->getRepository(Category::class);
	$categories = $repository->findAll();
	
	return $view->render($response, 'categories/list.phtml', [
		'categories' => $categories
	]);
});
	

$map->get('categories.create', '/categories/create', function($requet, $response) use ($view) {
	return $view->render($response, 'categories/create.phtml');
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
