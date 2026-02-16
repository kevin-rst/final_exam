<?php

use app\controllers\BesoinController;
use app\controllers\DonController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	$router->get('/', function() use ($app) {
		$app->render('index');	
		
	});

	$router->group('/besoins', function(Router $router) use ($app) {
		$router->get('/showForm', [BesoinController::class, 'showForm']);
	});

	$router->group('/dons', function(Router $router) use ($app) {
		$router->get('/showForm', [DonController::class, 'showForm']);
	});

}, [ SecurityHeadersMiddleware::class ]);