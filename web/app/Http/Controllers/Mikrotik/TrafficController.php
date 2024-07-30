<?php

namespace App\Http\Controllers\Mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \RouterOS\Client;
use \RouterOS\Query;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class TrafficController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        $this->client = new Client([
            'host' => config('services.mikrotik.host'),
            'user' => config('services.mikrotik.user'),
            'pass' => config('services.mikrotik.pass'),
            'port' => config('services.mikrotik.port'),
            'timeout' => 300,
            'socket_timeout' => 300,
            'socket_blocking' => false,
        ]);

    }

    public function index(Request $request)
    {
        $this->middleware('auth');
        $query = (new Query('/interface/print'));
        $result = $this->client->qr($query);

        $value = [];
        foreach ($result as $value) {
            $listInterface[] = $value["name"];
        }
        return view("mikrotik.traffic.all", compact('listInterface'));
    }

    public function addTraffic()
    {
        try {
            $query = (new Query("/interface/print"));

            $result = $data = [];
            $now = Carbon::now('Asia/Jakarta')->toDateTimeString();
            foreach ($this->client->qr($query) as $value) {
                $query2 = (new Query("/interface/monitor-traffic"));
                $query2->equal("interface", $value["name"]);
                $query2->equal("once");
                $data = $this->client->qr($query2);

                $result[] = [
                    "name" => $value["name"],
                    "tx" => $data[0]["tx-bits-per-second"],
                    "rx" => $data[0]["rx-bits-per-second"],
                    "created_at" => $now,
                    "updated_at" => $now,
                ];
            }

            $process = DB::table("traffic");
            $process->insert($result);

            return response()->json([
                "status" => "success",
                "message" => "Data has been updated."
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage()
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                "status" => "error",
                "message" => $ex->getMessage()
            ]);
        }
    }


    public function getCurrentTraffic(Request $request)
    {
        $this->middleware('auth');
        $name = $request->input("name");

        $q_labels = DB::table("traffic");
        $q_labels->orderByDesc("created_at");
        $q_labels->where("name", $name);
        $q_labels->take(10);

        // $trafficdata = $q_labels->get();
        // array_reverse($trafficdata);
        $tx = $rx = $labels = $rest = [];
        foreach ($q_labels->get() as $value) {
            if (!in_array($value->created_at, $labels) && count($labels) <= 10)
                $labels[] = $value->created_at;

            $process = "";
            $process = DB::table("traffic");
            $process->select("tx", "rx", "created_at", "name");
            $process->where("name", $name);
            $process->where("created_at", $value->created_at);
            $process->orderByDesc("created_at");
            $process->take(1);
            $result = $process->get();

            if (in_array($value->created_at, $labels)) {
                $tx[] = $value->tx;
                $rx[] = $value->rx;
            }
        }

        $tx= array_reverse($tx);
        $rx = array_reverse($rx);
        $labels = array_reverse($labels);

        $out["labels"] = $labels;
        $out["datasets"] = [
            [
                "label" => "tx",
                "data" => $tx,
                "backgroundColor" => "rgb(255, 99, 132)",
                "borderColor" => "rgb(255, 99, 132)",
                "yAxisID" => "y"
            ],
            [
                "label" => "rx",
                "data" => $rx,
                "backgroundColor" => "rgb(255, 159, 64)",
                "borderColor" => "rgb(255, 159, 64)",
                "yAxisID" => "y"
            ]
        ];

        return json_encode($out);
    }
}