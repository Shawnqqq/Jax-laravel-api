<?php

$router->group(['middleware' => ['auth:web']], function($router) {
    // 我的权限
    $router->get('/permissions/my', 'PermissionController@my');
});

$router->group(['middleware' => ['AdminPermission:user-manage'], 'middleware' => ['AdminPermission:permission-manage']], function($router) {
    $router->get('/users', 'UserController@index');
});

$router->group(['middleware' => ['AdminPermission:user-manage']], function($router) {
    $router->get('/users/{id}', 'UserController@show');
    $router->put('/users/{id}', 'UserController@update');
    $router->post('/users', 'UserController@store');
});

$router->group(['middleware' => ['AdminPermission:permission-manage']], function($router) {
    // 所有权限
    $router->get('/permissions', 'PermissionController@index');
    // 角色管理
    $router->get('/roles', 'RoleController@index');
    $router->post('/roles', 'RoleController@store');
    $router->get('/roles/{id}', 'RoleController@show');
    $router->put('/roles/{id}', 'RoleController@update');
    $router->delete('/roles/{id}', 'RoleController@destroy');
    // 管理员管理
    $router->get('/administrators', 'AdministratorController@index');
    $router->post('/administrators', 'AdministratorController@store');
    $router->get('/administrators/{id}', 'AdministratorController@show');
    $router->put('/administrators/{id}', 'AdministratorController@update');
    $router->delete('/administrators/{id}', 'AdministratorController@destroy');
});

$router->group(['middleware' => ['AdminPermission:blog-manage']], function($router) {
    $router->get('/blog', 'BlogController@index');
    $router->get('/blog/{id}', 'BlogController@show');
    $router->post('/blog', 'BlogController@store');
    $router->put('/blog/{id}', 'BlogController@update');
    $router->delete('/blog/{id}', 'BlogController@destroy');
    // 博客分类
    $router->get('/blog-tags', 'BlogTagController@index');
    $router->post('/blog-tags', 'BlogTagController@store');
    $router->put('/blog-tags/{id}', 'BlogTagController@update');
    $router->delete('/blog-tags/{id}', 'BlogTagController@destroy');
    $router->get('/blog-tags-sort', 'BlogTagController@sort');
    $router->get('/blog-tags-status/{id}', 'BlogTagController@status');
    // 博客分类组
    $router->get('/blog-tags-group', 'BlogTagGroupController@index');
    $router->post('/blog-tags-group', 'BlogTagGroupController@store');
    $router->put('/blog-tags-group/{id}', 'BlogTagGroupController@update');
    $router->delete('/blog-tags-group/{id}', 'BlogTagGroupController@destroy');
    $router->get('/blog-tags-group-sort', 'BlogTagGroupController@sort');
});

