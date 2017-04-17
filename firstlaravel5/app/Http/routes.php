<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');
Route::get('template', 'HomeController@template');

// controllers/system/user
Route::group(['namespace' => 'System\Users'], function()
{
	Route::controllers([
		'users' 			=> 'UsersController',
		'user-groups' 	=> 'UsergroupsController'
	]);
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
