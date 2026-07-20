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
$routes->get('/typesoperation', 'TypeOperationController::index');
$routes->get('/typesoperation/create', 'TypeOperationController::create');
$routes->post('/typesoperation/store', 'TypeOperationController::store');
$routes->post('/typesoperation/delete', 'TypeOperationController::delete');
$routes->get('/typesoperation/edit/(:num)', 'TypeOperationController::edit/$1');
$routes->post('/typesoperation/update/(:num)', 'TypeOperationController::update/$1');

$routes->get('/bareme', 'BaremeController::index');
$routes->get('/bareme/create', 'BaremeController::create');
$routes->post('/bareme/store', 'BaremeController::store');
$routes->get('/bareme/edit/(:num)', 'BaremeController::edit/$1');
$routes->post('/bareme/update/(:num)', 'BaremeController::update/$1');
$routes->post('/bareme/delete', 'BaremeController::delete');
$routes->match(['get', 'post'], '/bareme/test', 'BaremeController::testFrais');
$routes->get('/bareme/api/frais', 'BaremeController::apiFrais');