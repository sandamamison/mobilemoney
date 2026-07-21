<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');

// Routes d'authentification
$routes->get('login', 'AuthController::index');
$routes->get('connexion', 'AuthController::index');
$routes->post('connexion', 'AuthController::connexion');
$routes->get('auth/login', 'AuthController::index');
$routes->post('auth/login', 'AuthController::connexion');
$routes->get('deconnexion', 'AuthController::deconnexion');
$routes->get('logout', 'AuthController::deconnexion');
$routes->get('auth/logout', 'AuthController::deconnexion');

// Routes client (protégées par ClientAuthFilter)
$routes->group('client', ['filter' => 'clientauth'], function ($routes) {
    $routes->get('dashboard', 'ClientController::dashboard');
    $routes->get('operations', 'ClientController::operations');
    $routes->get('depot', 'OperationController::depot');
    $routes->post('depot', 'OperationController::doDepot');
    $routes->get('retrait', 'OperationController::retrait');
    $routes->post('retrait', 'OperationController::doRetrait');
    $routes->get('transfert', 'OperationController::transfert');
    $routes->post('transfert', 'OperationController::doTransfert');
    $routes->get('transfert-multiple', 'OperationController::transfertMultiple');
    $routes->post('transfert-multiple', 'OperationController::doTransfertMultiple');
    $routes->get('pourcentage', 'ClientController::pourcentage');
});
$routes->get('/operateur', 'OperateurController::index');
$routes->get('/gains', 'GainController::index');
$routes->get('/gains/historique', 'GainController::historique');
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

$routes->get('/comptes', 'CompteController::index');
$routes->get('/comptes/show/(:num)', 'CompteController::show/$1');
$routes->post('/comptes/bloque/(:num)', 'CompteController::bloque/$1');
$routes->post('/comptes/debloque/(:num)', 'CompteController::debloque/$1');

// Routes Autres Opérateurs
$routes->get('/autres-operateurs', 'AutreOperateurController::index');
$routes->get('/autres-operateurs/create', 'AutreOperateurController::create');
$routes->post('/autres-operateurs/store', 'AutreOperateurController::store');
$routes->get('/autres-operateurs/edit/(:num)', 'AutreOperateurController::edit/$1');
$routes->post('/autres-operateurs/update/(:num)', 'AutreOperateurController::update/$1');
$routes->post('/autres-operateurs/toggle/(:num)', 'AutreOperateurController::toggleActif/$1');
$routes->post('/autres-operateurs/delete', 'AutreOperateurController::delete');

// Routes Préfixes Externes
$routes->get('/prefixes-externes', 'PrefixeAutreOperateurController::index');
$routes->get('/prefixes-externes/create', 'PrefixeAutreOperateurController::create');
$routes->post('/prefixes-externes/store', 'PrefixeAutreOperateurController::store');
$routes->get('/prefixes-externes/edit/(:num)', 'PrefixeAutreOperateurController::edit/$1');
$routes->post('/prefixes-externes/update/(:num)', 'PrefixeAutreOperateurController::update/$1');
$routes->post('/prefixes-externes/toggle/(:num)', 'PrefixeAutreOperateurController::toggleActif/$1');
$routes->post('/prefixes-externes/delete', 'PrefixeAutreOperateurController::delete');

// Routes Règlements (Montants à envoyer)
$routes->get('/reglements', 'ReglementOperateurController::index');


$routes->post('pourcentage/add', 'PourcentageController::store');