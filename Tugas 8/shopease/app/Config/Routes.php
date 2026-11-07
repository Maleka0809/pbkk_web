<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend Public Routes
$routes->get('/', 'HomeController::index');
$routes->get('products', 'ProductController::index');
$routes->get('products/(:segment)', 'ProductController::detail/$1');

// Auth Routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::attemptRegister');
$routes->get('logout', 'AuthController::logout');

// Customer Protected Routes (Requires Login)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Shopping Cart
    $routes->get('cart', 'CartController::index');
    $routes->post('cart/add', 'CartController::add');
    $routes->post('cart/update', 'CartController::update');
    $routes->get('cart/delete/(:num)', 'CartController::delete/$1');
    $routes->get('cart/clear', 'CartController::clear');

    // Checkout
    $routes->get('checkout', 'CheckoutController::index');
    $routes->post('checkout/process', 'CheckoutController::process');

    // Customer Orders
    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/(:num)', 'OrderController::detail/$1');
    $routes->post('orders/(:num)/upload-payment', 'OrderController::uploadPayment/$1');
});

// Admin & Operator Protected Routes
$routes->group('admin', ['filter' => 'role:admin,operator'], static function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Categories Management
    $routes->get('categories', 'Admin\CategoryController::index');
    $routes->post('categories/store', 'Admin\CategoryController::store');
    $routes->post('categories/update/(:num)', 'Admin\CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\CategoryController::delete/$1');

    // Products Management
    $routes->get('products', 'Admin\ProductController::index');
    $routes->post('products/store', 'Admin\ProductController::store');
    $routes->post('products/update/(:num)', 'Admin\ProductController::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\ProductController::delete/$1');

    // Orders Management
    $routes->get('orders', 'Admin\OrderController::index');
    $routes->get('orders/(:num)', 'Admin\OrderController::detail/$1');
    $routes->post('orders/(:num)/status', 'Admin\OrderController::updateStatus/$1');

    // Payment Verification
    $routes->get('payments', 'Admin\PaymentController::index');
    $routes->get('payments/verify/(:num)', 'Admin\PaymentController::verify/$1');
    $routes->post('payments/reject/(:num)', 'Admin\PaymentController::reject/$1');

    // Shipment Management
    $routes->get('shipments', 'Admin\ShipmentController::index');
    $routes->post('shipments/update/(:num)', 'Admin\ShipmentController::update/$1');

    // Customer & Staff List
    $routes->get('users', 'Admin\UserController::index');

    // Sales Reports
    $routes->get('reports', 'Admin\ReportController::index');
});
