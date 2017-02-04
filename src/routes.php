<?php

use Aura\Router\RouterContainer;
use Slim\Views\PhpRenderer;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use App\Entity\Post;
use App\Entity\Category;
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals(
	$_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$routerContainer = new RouterContainer();

$generator = $routerContainer->getGenerator();

$map = $routerContainer->getMap();

$view = new PhpRenderer(__DIR__ . '/../templates/');


$entityManager = getEntityManager();


$map->get('home', '/', function(ServerRequestInterface $requet, $response) use ($view, $entityManager) {
	
	$postRepository = $entityManager->getRepository(Post::class);
	
	
	$categoryRepository = $entityManager->getRepository(Category::class);
	$categories = $categoryRepository->findAll();
	
	$data = $requet->getQueryParams();
	
	if(isset($data['search']) && $data['search'] != '') {
		$queryBuilder = $postRepository->createQueryBuilder('p');
		$queryBuilder->join('p.categories', 'c')
		             ->where($queryBuilder->expr()->eq('c.id', $data['search']));
		
		$posts = $queryBuilder->getQuery()->getResult();
		             
	} else {
		$posts = $postRepository->findAll();
	}
	
	
	return $view->render($response, 'home.phtml', [
		'posts' => $posts,
		'categories' => $categories
	]);
	
});


require_once __DIR__. '/categories.php';
require_once __DIR__. '/posts.php';

$matcher = $routerContainer->getMatcher();


$route = $matcher->match($request);


foreach ($route->attributes as $key => $value) {
	$request = $request->withAttribute($key, $value);
}


$callable = $route->handler;


/** @var Response $response */
$response = $callable($request, new Response());


if($response instanceof Zend\Diactoros\Response\RedirectResponse) {
	header("Location:{$response->getHeader("location")[0]}");
} else if($response instanceof Response) {
	echo $response->getBody();
}

