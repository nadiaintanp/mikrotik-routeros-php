<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::group([
	"namespace" => "App\Http\Controllers\Mikrotik"
], function () {
	Route::group([
		"as" => "verify",
		"prefix" => "health"
	], function () {
		Route::get("db", "HealthcheckController@verifyDB")->name(".db");
		Route::get("mikrotik", "HealthcheckController@verifyMikrotik")->name(".mikrotik");

		Route::get("reboot", "HealthcheckController@reboot")->name(".reboot");
	});

	Route::group([
		"prefix" => "voucher",
		"as" => "voucher"
	], function () {
		Route::get("/", "VoucherController@index")->name(".list");
	});

	Route::group([
		"prefix" => "hotspot",
		"as" => "hotspot"
	], function () {
		// Route::get("{profile?}", "HotspotController@index");
		Route::post("user/create", "UserController@generate")->name(".user.add");

		Route::group([
			"prefix" => "user",
			"as" => ".user"
		], function () {
			// Route::get("{profile?}", "HotspotController@index");
			Route::get("/{profile?}", "UserController@index")->name(".list");
			Route::post("data", "UserController@userData")->name(".data");
			Route::post("create", "UserController@generate")->name(".add");
			Route::post("edit", "UserController@userEdit")->name(".edit");
			Route::post("del", "UserController@userRemove")->name(".delete");

			Route::post("create-profile", "UserController@profileAdd")->name(".profile.add");
			Route::post("data-profile", "UserController@profileData")->name(".profile.data");
			Route::post("delete-profile", "UserController@profileRemove")->name(".profile.delete");
			// Route::get("view", "UserController@")
		});
	});

	Route::group([
		"prefix" => "logs",
		"as" => "logs",
	], function () {
		Route::get("/", "LogController@index");

		Route::get("view/{type?}", "LogController@view")->name(".view");
	});

	Route::group([
		"prefix" => "traffic",
		"as" => "traffic.monitor",
	], function () {

		Route::get("/", ["uses" => "TrafficController@getCurrentTraffic"])->name(".list");
		Route::get("monitor/add", ["uses" => "TrafficController@addTraffic"])->name(".add");
		Route::get("detail", "TrafficController@index")->name(".detail");
	});

	Route::group([
		"prefix"	=> "scheduler",
		"as"		=> "scheduler"
	], function() {
		Route::get("/", "SystemController@scheduler")->name(".list");
		Route::post("scheduler/view", "SystemController@schedulerData")->name(".data");
		Route::post("scheduler/add", "SystemController@schedulerAdd")->name(".add");
		// Route::post("scheduler/edit", "SystemController@schedulerAdd")->name(".edit");
		Route::post("scheduler/remove", "SystemController@schedulerRemove")->name(".delete");
		
	});
});
