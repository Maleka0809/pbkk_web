<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 * ROUTING SHOP EASE - CODEIGNITER 4
 * Berdasarkan Roadmap Implementasi Authentication System (7 Tahapan)
 */

// 1. Public Routes
$routes->get('/', 'HomeController::index');
$routes->get('products', 'ProductController::index');
$routes->get('products/(:segment)', 'ProductController::detail/$1');

// 2. Authentication Routes (Tahap 5 Roadmap & HOL)
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::store');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');

$routes->get('logout', 'AuthController::logout');
$routes->post('logout', 'AuthController::logout');

// 3. Customer Protected Routes (Tahap 7 Filter: auth:customer)
$routes->group('customer', ['filter' => 'auth:customer'], static function ($routes) {
    $routes->get('/', 'Customer\DashboardController::index');
    $routes->get('dashboard', 'Customer\DashboardController::index');
});

// 4. Operator Protected Routes (Tahap 7 Filter: auth:operator)
$routes->group('operator', ['filter' => 'auth:operator'], static function ($routes) {
    $routes->get('/', 'Operator\DashboardController::index');
    $routes->get('dashboard', 'Operator\DashboardController::index');
});

// 5. Admin Protected Routes (Tahap 7 Filter: auth:admin)
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('categories', 'Admin\CategoryController::index');
    $routes->get('products', 'Admin\ProductController::index');
    $routes->get('orders', 'Admin\OrderController::index');
    $routes->get('users', 'Admin\UserController::index');
});

// 6. Generic Auth Protected Routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('cart', 'CartController::index');
    $routes->post('cart/add', 'CartController::add');
    $routes->get('checkout', 'CheckoutController::index');
    $routes->get('orders', 'OrderController::index');
});
