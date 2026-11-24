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

// Products (master data: superadmin only)
$routes->get('product', 'Product::index');
$routes->get('product/add_product', 'Product::addProduct', ['filter' => 'roles:superadmin']);
$routes->get('product/add_product/(:num)', 'Product::addProduct/$1', ['filter' => 'roles:superadmin']);
$routes->get('product/select_product/(:num)', 'Product::selectProduct/$1');
$routes->post('product/save_product', 'Product::saveProduct', ['filter' => 'roles:superadmin']);
$routes->post('product/save_product/(:num)', 'Product::saveProduct/$1', ['filter' => 'roles:superadmin']);

// Branches (master data: superadmin only)
$routes->get('branch', 'Branch::index');
$routes->get('branch/add_branch', 'Branch::addBranch', ['filter' => 'roles:superadmin']);
$routes->get('branch/add_branch/(:num)', 'Branch::addBranch/$1', ['filter' => 'roles:superadmin']);
$routes->post('branch/save_branch', 'Branch::saveBranch', ['filter' => 'roles:superadmin']);
$routes->post('branch/save_branch/(:num)', 'Branch::saveBranch/$1', ['filter' => 'roles:superadmin']);

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

// Dealers (master data: superadmin only) & sales
$routes->get('dealer', 'Dealer::index');
$routes->get('dealer/add_dealer', 'Dealer::addDealer', ['filter' => 'roles:superadmin']);
$routes->get('dealer/add_dealer/(:num)', 'Dealer::addDealer/$1', ['filter' => 'roles:superadmin']);
$routes->post('dealer/save_dealer', 'Dealer::saveDealer', ['filter' => 'roles:superadmin']);
$routes->post('dealer/save_dealer/(:num)', 'Dealer::saveDealer/$1', ['filter' => 'roles:superadmin']);

$routes->get('sales', 'Sales::index');
$routes->get('sales/view_sale/(:num)', 'Sales::viewSale/$1');
$routes->get('sales/add_sale', 'Sales::addSale');
$routes->get('sales/add_sale/(:num)', 'Sales::addSale/$1');
$routes->post('sales/save_sale', 'Sales::saveSale');
$routes->post('sales/save_sale/(:num)', 'Sales::saveSale/$1');
