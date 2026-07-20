<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/operateur', 'OperateurController::index');
$routes->get('/prefixe', 'PrefixeController::index');
$routes->get('/prefixe/create', 'PrefixeController::create');
$routes->post('/prefixe/store', 'PrefixeController::store');
$routes->post('/prefixe/delete', 'PrefixeController::delete');