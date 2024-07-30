<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// require_once "mikrotik.php";

Route::get('/', 'App\Http\Controllers\HomeController@index');

Route::group([
	"as"		=> "home",
	"prefix"	=> "home",
	"namespace"	=> "App\Http\Controllers"
], function(){
	Route::get('/', 'HomeController@index');
	Route::get('voucher', 'HomeController@voucher')->name(".voucher");
	Route::get('info', 'HomeController@info')->name(".info");
});

Route::group([
	"as"	=> "config"
], function(){
	Route::get('/config', 'App\Http\Controllers\ConfigController@index');
	Route::put('/config/update/{id}', 'App\Http\Controllers\ConfigController@update')->name('.update');
});

Route::group(['namespace' => 'App\Http\Controllers\Profile'], function (){ 
	Route::get('/profile', 'ProfileController@index')->name('profile');
	Route::put('/profile/update/profile/{id}', 'ProfileController@updateProfile')->name('profile.update.profile');
	Route::put('/profile/update/password/{id}', 'ProfileController@updatePassword')->name('profile.update.password');
	Route::put('/profile/update/avatar/{id}', 'ProfileController@updateAvatar')->name('profile.update.avatar');
});

Route::group(['namespace' => 'App\Http\Controllers\Error'], function (){ 
	Route::get('/unauthorized', 'ErrorController@unauthorized')->name('unauthorized');
});

Route::group(['namespace' => 'App\Http\Controllers\User'], function (){ 
	// User
	Route::group([
		"prefix"	=> "user",
		"as"		=> "user"
	], function(){
		Route::get('/', 'UserController@index');
		Route::get('/create', 'UserController@create')->name('.create');
		Route::post('/store', 'UserController@store')->name('.store');
		Route::get('/edit/{id}', 'UserController@edit')->name('.edit');
		Route::put('/update/{id}', 'UserController@update')->name('.update');
		Route::get('/edit/password/{id}', 'UserController@editPassword')->name('.edit.password');
		Route::put('/update/password/{id}', 'UserController@updatePassword')->name('.update.password');
		Route::get('/show/{id}', 'UserController@show')->name('.show');
		Route::get('/destroy/{id}', 'UserController@destroy')->name('.destroy');
	});

	// Roles
	Route::group([
		"prefix"	=> "role",
		"as"		=> "role"
	], function(){
		Route::get('/', 'RoleController@index');
		Route::get('/create', 'RoleController@create')->name('.create');
		Route::post('/store', 'RoleController@store')->name('.store');
		Route::get('/edit/{id}', 'RoleController@edit')->name('.edit');
		Route::put('/update/{id}', 'RoleController@update')->name('.update');
		Route::get('/show/{id}', 'RoleController@show')->name('.show');
		Route::get('/destroy/{id}', 'RoleController@destroy')->name('.destroy');
	});
});
