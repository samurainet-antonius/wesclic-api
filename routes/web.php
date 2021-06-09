<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$version = 'v1';


$router->group(['prefix' => 'public/api/'.$version],function() use($router,$version){

      // auth
      $router->group(['prefix' => 'auth'], function() use($router, $version){
          $router->post('/login',$version.'\AuthController@login');
          $router->post('/registration', $version.'\AuthController@registration');
          $router->post('/activation', $version.'\AuthController@activation');
          $router->post('/request_password', $version.'\AuthController@requestPassword');
          $router->get('/check_token', $version.'\AuthController@checkTokenResetPassword');
          $router->post('/reset_password', $version.'\AuthController@resetPassword');
      });

      // user
      $router->group(['prefix' => 'user','middleware' => 'jwt.auth'], function() use($router, $version){

          // user
          $router->get('/profile',$version.'\UserController@profile');
          $router->post('/update',$version.'\UserController@update');
          $router->post('/change_password',$version.'\UserController@changePassword');


          // dokumen
          $router->group(['prefix' => 'dokumen'], function() use($router, $version){
              $router->get('/',$version.'\UploadFileController@show');
              $router->post('/upload',$version.'\UploadFileController@create');
          });

          // bank
          $router->group(['prefix' => 'bank'], function() use($router, $version){
              $router->get('/',$version.'\BankController@show');
              $router->post('/create',$version.'\BankController@create');
              $router->delete('/delete/{uuid}',$version.'\BankController@delete');
          });


          // toko
          $router->group(['prefix' => 'toko'], function() use($router, $version){
              $router->get('/',$version.'\TokoController@show');
              $router->post('/create',$version.'\TokoController@create');
              $router->post('/update/{uuid}',$version.'\TokoController@edit');
              $router->delete('/delete',$version.'\TokoController@delete');
          });

      });

});
