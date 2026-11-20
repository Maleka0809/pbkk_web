<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 * ROUTING SHOP EASE - CODEIGNITER 4
 * Tugas 10: Product Management (CRUD Kategori, Produk & Image Management)
 */

// 1. Frontend Public Routes
$routes->get('/', 'HomeController::index');
$routes->get('products', 'ProductController::index');
$routes->get('products/(:segment)', 'ProductController::detail/$1');

// 2. Authentication Routes
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::store');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');

$routes->get('logout', 'AuthController::logout');
$routes->post('logout', 'AuthController::logout');

// 3. Customer Portal
$routes->group('customer', ['filter' => 'auth:customer'], static function ($routes) {
    $routes->get('/', 'Customer\DashboardController::index');
    $routes->get('dashboard', 'Customer\DashboardController::index');
});

// 4. Operator Portal
$routes->group('operator', ['filter' => 'auth:operator'], static function ($routes) {
    $routes->get('/', 'Operator\DashboardController::index');
    $routes->get('dashboard', 'Operator\DashboardController::index');
});

// 5. Admin & Operator Product Management (HOL CRUD Spesifikasi)
$routes->group('admin', ['filter' => 'role:admin,operator'], static function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // CRUD Categories
    $routes->get('categories', 'Admin\CategoryController::index');
    $routes->get('categories/create', 'Admin\CategoryController::create');
    $routes->post('categories/store', 'Admin\CategoryController::store');
    $routes->get('categories/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\CategoryController::delete/$1');

    // CRUD Products & Image Management
    $routes->get('products', 'Admin\ProductController::index');
    $routes->get('products/create', 'Admin\ProductController::create');
    $routes->post('products/store', 'Admin\ProductController::store');
    $routes->get('products/edit/(:num)', 'Admin\ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\ProductController::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\ProductController::delete/$1');
    $routes->get('products/delete-image/(:num)', 'Admin\ProductController::deleteImage/$1');
    $routes->get('products/set-primary/(:num)/(:num)', 'Admin\ProductController::setPrimaryImage/$1/$2');
});
