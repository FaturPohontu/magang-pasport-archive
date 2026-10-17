<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    
    // Fitur Scan Resi & Input Paspor
    $routes->get('scan', 'HomeController::index');                   // Halaman utama scan/form
    $routes->post('scan/process-ocr', 'HomeController::processOcr');  // Endpoint kirim foto -> balikkan teks OCR
    $routes->post('scan/save', 'HomeController::saveToSheet');       // Endpoint simpan data akhir ke Google Sheets
    
    $routes->get('users', 'UserController::index');
});