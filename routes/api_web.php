<?php


$router->group(['middleware' => ['auth:web']], function($router) {
    $router->get('/users/user-info', 'UsersController@userInfo');
    $router->put('/users/user-info', 'UsersController@userInfoUpdate');
});
$router->get('/users/dev-login', 'UsersController@devLogin');

$router->post('/sms/register-code', 'SmsController@registerCode');
$router->post('/sms/register-phone', 'SmsController@registerPhone');

$router->post('/auth/email-register', 'AuthController@emialRegister');
$router->post('/auth/email-login', 'AuthController@emailLogin');
$router->get('/auth/logout', 'AuthController@logout');

$router->post('/auth/phone-login','AuthController@phoneLogin');
$router->group(['middleware' => ['AdminPermission:permission-manage']], function($router) {
    $router->put('/auth/reset-password','AuthController@updatePassword');
});

$router->get('/oauth/wechat/callback', 'AuthController@wechatCallback');

// 博客
$router->get('/blog', 'BlogController@index');
$router->get('/blog/{id}', 'BlogController@show');
$router->get('/blog-tags', 'BlogController@tags');
$router->post('/blog', 'BlogController@store');