<?php

use app\controllers\VilleController;
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

	$router->get('/', [VilleController::class, 'getVilleDetails']);

	$router->group('/besoins', function(Router $router) use ($app) {
		$router->get('/showForm', [BesoinController::class, 'showForm']);
		$router->post('/create', [ BesoinController::class, 'create' ]);
	});

	$router->group('/dons', function(Router $router) use ($app) {
		$router->get('/showForm', [DonController::class, 'showForm']);
		$router->post('/create', [ DonController::class, 'create' ]);
		$router->get('/dispatch', [ DonController::class, 'dispatch' ]);

	});

}, [ SecurityHeadersMiddleware::class ]);