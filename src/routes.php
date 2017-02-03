<?php

use Zend\Diactoros\ServerRequestFactory;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Response;
use Slim\Views\PhpRenderer;
use App\Entity\Category;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

$request = ServerRequestFactory::fromGlobals(
	$_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$routerContainer = new RouterContainer();

$generator = $routerContainer->getGenerator();

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
	
$map->post('categories.store', '/categories/store', function(ServerRequestInterface $requet, $response) 
		use ($view, $entityManager, $generator) {
	
	$data = $requet->getParsedBody();
	
	$category = new Category();
	$category->setName($data['name']);
	
	$entityManager->persist($category);
	$entityManager->flush();
	
	$uri = $generator->generate('categories.list');
	
	return new RedirectResponse($uri);
});


$map->get('categories.edit', '/categories/{id}/edit', function(ServerRequestInterface $requet, $response) use ($view, $entityManager) {
	
	$id = $requet->getAttribute('id');
	
	$repositoty = $entityManager->getRepository(Category::class);
	
	$category = $repositoty->find($id);
	
	return $view->render($response, 'categories/edit.phtml', [
		'category' => $category
	]);
});


$map->post('categories.update', '/categories/{id}/update', function(ServerRequestInterface $requet, $response)
		use ($view, $entityManager, $generator) {

			$id = $requet->getAttribute('id');
			
			$repositoty = $entityManager->getRepository(Category::class);
			
			$category = $repositoty->find($id);
			
				
			$data = $requet->getParsedBody();

			$category->setName($data['name']);

			$entityManager->flush();

			$uri = $generator->generate('categories.list');

			return new RedirectResponse($uri);
});

$map->get('categories.remove', '/categories/{id}/remove', function(ServerRequestInterface $requet, $response)
		use ($view, $entityManager, $generator) {

			$id = $requet->getAttribute('id');
				
			$repositoty = $entityManager->getRepository(Category::class);
				
			$category = $repositoty->find($id);
					
			$entityManager->remove($category);
			$entityManager->flush();

			$uri = $generator->generate('categories.list');

			return new RedirectResponse($uri);
});

$matcher = $routerContainer->getMatcher();


$route = $matcher->match($request);


foreach ($route->attributes as $key => $value) {
	$request = $request->withAttribute($key, $value);
}


$callable = $route->handler;


/** @var Response $response */
$response = $callable($request, new Response());

if($response instanceof RedirectResponse) {
	header("Location:{$response->getHeader("location")[0]}");
} else if($response instanceof Response) {
	echo $response->getBody();
}

