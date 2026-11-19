<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');


$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // Fitur yang dapat diakses Admin dan Petugas
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('arsip', 'ArsipController::index');

    // Khusus Petugas
    $routes->group('', ['filter' => 'role:petugas'], static function ($routes) {

        $routes->get('scan', 'HomeController::index');
        $routes->post('scan/process-ocr', 'HomeController::processOcr');
        $routes->post('scan/save', 'HomeController::saveToSheet');
        $routes->post('scan/process-batch', 'HomeController::processBatch');
        $routes->post('scan/save-batch', 'HomeController::saveBatch');

    });

    // Khusus Admin
    $routes->group('', ['filter' => 'role:admin'], static function ($routes) {

        $routes->get('users', 'UserController::index');
        $routes->post('users/store', 'UserController::store');
        $routes->post('users/deactivate/(:num)', 'UserController::deactivate/$1');

    });

});