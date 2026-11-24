<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public
$routes->get('login', 'Login::index');
$routes->post('login/login_process', 'Login::loginProcess');

// Everything else goes through the auth filter (see Config\Filters).
$routes->get('/', 'App::index');
$routes->post('app/logout', 'App::logout');

// Products
$routes->get('product', 'Product::index');
$routes->get('product/add_product', 'Product::addProduct');
$routes->get('product/add_product/(:num)', 'Product::addProduct/$1');
$routes->get('product/select_product/(:num)', 'Product::selectProduct/$1');
$routes->post('product/save_product', 'Product::saveProduct');
$routes->post('product/save_product/(:num)', 'Product::saveProduct/$1');

// Branches
$routes->get('branch', 'Branch::index');
$routes->get('branch/add_branch', 'Branch::addBranch');
$routes->get('branch/add_branch/(:num)', 'Branch::addBranch/$1');
$routes->post('branch/save_branch', 'Branch::saveBranch');
$routes->post('branch/save_branch/(:num)', 'Branch::saveBranch/$1');

// Stocks
$routes->get('stock', 'Stock::index');
$routes->get('stock/add_stock', 'Stock::addStock');
$routes->get('stock/add_stock/(:num)', 'Stock::addStock/$1');
$routes->get('stock/edit_stock/(:num)', 'Stock::editStock/$1');
$routes->post('stock/save_stock', 'Stock::saveStock');
$routes->post('stock/save_stock/(:num)', 'Stock::saveStock/$1');
$routes->post('stock/update_stock', 'Stock::updateStock');
$routes->post('stock/update_stock/(:num)', 'Stock::updateStock/$1');

// Inventory & cashbook
$routes->get('inventory', 'Inventory::index');
$routes->get('cashbook', 'Cashbook::index');
$routes->get('cashbook/cash_in', 'Cashbook::cashIn');
$routes->get('cashbook/cash_out', 'Cashbook::cashOut');

// Dealers & sales
$routes->get('dealer', 'Dealer::index');
$routes->get('dealer/add_dealer', 'Dealer::addDealer');
$routes->get('dealer/add_dealer/(:num)', 'Dealer::addDealer/$1');
$routes->post('dealer/save_dealer', 'Dealer::saveDealer');
$routes->post('dealer/save_dealer/(:num)', 'Dealer::saveDealer/$1');

$routes->get('sales', 'Sales::index');
$routes->get('sales/add_sale', 'Sales::addSale');
$routes->get('sales/add_sale/(:num)', 'Sales::addSale/$1');
$routes->post('sales/save_sale', 'Sales::saveSale');
$routes->post('sales/save_sale/(:num)', 'Sales::saveSale/$1');
