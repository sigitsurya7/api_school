<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('v1', function($routes){
    // Auth Services
    $routes->group('auth', function($routes){
        $routes->post('login', 'AuthServices\Auth::login');
        $routes->get('', 'AuthServices\Auth::get', ['filter' => 'auth']);
        $routes->post('register', 'AuthServices\Auth::register');
        $routes->post('recovery', 'AuthServices\Auth::recovery');
        // $routes->post('del', 'Annoucement\AnnouncementCtrl::delt');
    });

    $routes->group('role', ['filter' => 'auth'], function($routes){
        $routes->post('', 'AuthServices\Role::post');
        $routes->get('(:any)', 'AuthServices\Role::get/$1');
        $routes->post('update/(:num)', 'AuthServices\Role::edit/$1');
        $routes->delete('(:num)', 'AuthServices\Role::delete/$1');
    });
    
    $routes->group('level', ['filter' => 'auth'], function($routes){
        $routes->post('', 'MasterServices\Level::post');
        $routes->get('(:any)', 'MasterServices\Level::get/$1');
        $routes->post('update/(:num)', 'MasterServices\Level::edit/$1');
        $routes->delete('(:num)', 'MasterServices\Level::delete/$1');
    });

    $routes->group('pengumuman', ['filter' => 'auth'], function($routes){
        $routes->post('', 'MasterServices\Pengumuman::post');
        $routes->get('(:any)', 'MasterServices\Pengumuman::get/$1');
        $routes->post('update/(:num)', 'MasterServices\Pengumuman::edit/$1');
        $routes->delete('(:num)', 'MasterServices\Pengumuman::delete/$1');
    });
});
