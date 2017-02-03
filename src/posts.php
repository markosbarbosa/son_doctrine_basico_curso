<?php

use App\Entity\Post;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;


$map->get('posts.list', '/posts', function($request, $response) use ($view, $entityManager) {
	
	$repository = $entityManager->getRepository(Post::class);
	$posts = $repository->findAll();

	return $view->render($response, 'posts/list.phtml', [
		'posts' => $posts
	]);
});
	

$map->get('posts.create', '/posts/create', function($request, $response) use ($view) {
	return $view->render($response, 'posts/create.phtml');
});
	
$map->post('posts.store', '/posts/store', function(ServerRequestInterface $request, $response) 
		use ($view, $entityManager, $generator) {
	
	$data = $request->getParsedBody();
	
	$post = new Post();
	$post->setTitle($data['title'])
	     ->setContent($data['content']);
	
	$entityManager->persist($post);
	$entityManager->flush();
	
	$uri = $generator->generate('posts.list');
	
	return new RedirectResponse($uri);
});


$map->get('posts.edit', '/posts/{id}/edit', function(ServerRequestInterface $request, $response) use ($view, $entityManager) {
	
	$id = $request->getAttribute('id');
	
	$repositoty = $entityManager->getRepository(Post::class);
	
	$post = $repositoty->find($id);
	
	return $view->render($response, 'posts/edit.phtml', [
		'post' => $post
	]);
});


$map->post('posts.update', '/posts/{id}/update', function(ServerRequestInterface $request, $response)
		use ($view, $entityManager, $generator) {

			$id = $request->getAttribute('id');
			
			$repositoty = $entityManager->getRepository(Post::class);
			
			$post = $repositoty->find($id);
			
				
			$data = $request->getParsedBody();

			
			$post->setTitle($data['title'])
			     ->setContent($data['content']);
				
			$entityManager->flush();

			$uri = $generator->generate('posts.list');
			
			return new RedirectResponse('/posts');
});

$map->get('posts.remove', '/posts/{id}/remove', function(ServerRequestInterface $request, $response)
		use ($view, $entityManager, $generator) {

			$id = $request->getAttribute('id');
				
			$repositoty = $entityManager->getRepository(Post::class);
				
			$post = $repositoty->find($id);
					
			$entityManager->remove($post);
			$entityManager->flush();

			$uri = $generator->generate('posts.list');

			return new RedirectResponse($uri);
});
