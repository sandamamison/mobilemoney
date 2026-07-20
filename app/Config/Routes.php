<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Routes d'authentification
$routes->get('login', 'AuthController::index');
$routes->post('connexion', 'AuthController::connexion');
$routes->get('deconnexion', 'AuthController::deconnexion');
$routes->get('logout', 'AuthController::deconnexion');

// Routes client (protégées par ClientAuthFilter)
$routes->group('client', ['filter' => 'clientauth'], function ($routes) {
    $routes->get('dashboard', 'ClientController::dashboard');
    $routes->get('operations', 'ClientController::operations');
    $routes->get('depot', 'OperationController::depot');
    $routes->post('depot', 'OperationController::doDepot');
    $routes->get('retrait', 'OperationController::retrait');
    $routes->post('retrait', 'OperationController::doRetrait');
    $routes->post('transfert', 'OperationController::transfert');
});
