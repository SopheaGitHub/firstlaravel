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

// controllers/common
Route::group(['namespace' => 'Common'], function()
{
	Route::controllers([
		'filemanager' 	=> 'FilemanagerController',
	]);
});

// controllers/system
Route::group(['namespace' => 'System'], function()
{
	Route::controllers([
		'settings' 		=> 'SettingsController',
	]);
});

// controllers/system/user
Route::group(['namespace' => 'System\Users'], function()
{
	Route::controllers([
		'users' 		=> 'UsersController',
		'user-groups' 	=> 'UsergroupsController',
	]);
});

// controllers/system/localisation
Route::group(['namespace' => 'System\Localisation'], function()
{
	Route::controllers([
		'languages'		=> 'LanguagesController',
		'currencies'	=> 'CurrenciesController',
		'countries'		=> 'CountriesController',
		'zones'			=> 'ZonesController',
		'geo-zones'		=>	'GeoZonesController'
	]);
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
