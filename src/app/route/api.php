<?php

require __DIR__ . '/../../../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('\App\Http\Controllers');
// Define routes

$router->get('/', 'ApiController@index');

$router->get('/category/{category}', 'ApiController@category');

$router->get('/categories', 'ApiController@categories');

// Run it!
$router->run();