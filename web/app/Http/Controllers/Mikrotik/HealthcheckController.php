<?php

namespace App\Http\Controllers\Mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use \RouterOS\Client;
use \RouterOS\Query;

class HealthcheckController extends Controller
{
	public function __construct()
	{
	}

	public function verifyDB()
	{
		try {
			$dbconnect = DB::connection()->getPDO();
			$dbname = DB::connection()->getDatabaseName();
			echo "Connected successfully to the database. Database name is :" . $dbname;
		} catch (Exception $e) {
			echo "Error in connecting to the database";
		}
	}

	public function verifyMikrotik()
	{
		try {
			$client = new Client([
				'host' => config('services.mikrotik.host'),
				'user' => config('services.mikrotik.user'),
				'pass' => config('services.mikrotik.pass'),
				'port' => config('services.mikrotik.port'),
				'timeout' => 5,
			]);

			$query =
				(new Query('/system/resource/monitor'));
			$query->equal("once");
			// $query->where("len");

			$response = $client->q($query)->r();

			echo "Success connecting to router device.";

			// $query =
			// 	(new Query('/user/active/listen'));
			// $response = $client->query($query)->read();
			return dd($response);
		} catch (Exception $e) {
			echo "Failed in connecting to router device. ERROR: " . $e->getMessage();
		}

	}

	public function reboot()
	{
		// dd("reboot");
		try {
			$this->middleware('auth');

			$client = new Client([
				'host' => config('services.mikrotik.host'),
				'user' => config('services.mikrotik.user'),
				'pass' => config('services.mikrotik.pass'),
				'port' => config('services.mikrotik.port'),
				'timeout' => 5,
			]);

			$query =
				(new Query('/system/reboot'));
			// $query->equal("once");

			$response = $client->q($query)->r();

			if ( ! empty($response) ) 
				throw new Exception("Failed to reboot", 1);

			$result = [
				"status"	=> "success",
				"message"	=> "Rebooting system"
			];

			return json_encode($result);
		} catch (Exception $e) {
			$result = [
				"status"	=> "error",
				"message"	=> $e->getMessage()
			];

			return json_encode($result);
		}

	}
}