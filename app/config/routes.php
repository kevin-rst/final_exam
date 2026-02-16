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

	$router->get('/', function() use ($app) {
		$app->render('index');
	} );

	$router->group('/besoins', function(Router $router) use ($app) {
		$router->get('/showForm', [BesoinController::class, 'showForm']);
		$router->post('/create', [ BesoinController::class, 'create' ]);
		$router->get('/list', [ BesoinController::class, 'getBesoinDetails' ]);
	});

	$router->group('/dons', function(Router $router) use ($app) {
		$router->get('/showForm', [DonController::class, 'showForm']);
		$router->post('/create', [ DonController::class, 'create' ]);
		$router->get('/dispatch', [ DonController::class, 'dispatch' ]);
	});

	$router->get('/ville/details', [VilleController::class, 'getVilleDetails']);

}, [ SecurityHeadersMiddleware::class ]);