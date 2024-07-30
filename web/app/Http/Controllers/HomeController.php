<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use \RouterOS\Client;
use \RouterOS\Query;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    public function index()
    {
        return view('home');
    }


    public function info()
    {
        $logs = $listInterface = $resources = [];
        $totActiveHotspot = $totUser = 0;

        $listData = [
            'resource' => ['/system/resource/print'],
            'routerboard' => ['/system/routerboard/print'],
            'user' => ['/ip/hotspot/user/print'],
            'hotspot' => ['/ip/hotspot/active/print'],
            'log' => ['/log/print', ["topics", "hotspot,info,debug"]],
            'listInterface' => ['/interface/print']
        ];

        foreach ($listData as $key => $value) {
            $query = "";
            $query = (new Query($value[0]));
            $getData = count($value) >= 2 ? $this->client->qr($query, $value[1]) : $this->client->qr($query);

            switch ($key) {
                case 'routerboard':
                    $resources["model"] = $getData[0]["model"];
                    break;

                case 'resource':
                    $resources["uptime"] = $getData[0]['uptime'];
                    $resources["board-name"] = $getData[0]["board-name"];
                    $resources["version"] = $getData[0]["version"];
                    $resources["cpu-load"] = $getData[0]["cpu-load"];
                    $resources["free-memory"] = $getData[0]["free-memory"];
                    $resources["free-hdd-space"] = $getData[0]["free-hdd-space"];
                    break;

                case 'user':
                    $totUser = count($getData);
                    break;

                case 'hotspot':
                    $totActiveHotspot = count($getData);
                    break;

                case 'listInterface':
                    $value = [];
                    foreach ($getData as $value) {
                        $listInterface[] = $value["name"];
                    }
                    break;

                case 'log':
                    foreach ($getData as $key => $value) {

                        $tempMsg = $value["message"];
                        $tempIf = substr($tempMsg, 0, 2);
                        $user = $tempIf == "->" ? explode(":", $tempMsg) : $user = ["", "", $tempMsg];
                        unset($user[0]);
                        $message = $user;

                        if (count($user) > 5) {
                            array_splice($user, 6);
                            $user = implode(":", $user);
                            $user = trim($user);
                        } else {
                            $user = trim($user[1]);
                        }
                        if (count($message) > 5) {
                            array_splice($message, 0, 5);
                            $message = implode(" ", $message);
                            $message = "trying to " . trim($message);
                        } else {
                            $message = trim($message[2]);
                        }

                        $logs[] = [
                            "time" => $value["time"],
                            "user" => $user,
                            "message" => $message
                        ];
                    }
                    break;
            }
        }

        return view(
            "mikrotik.dashboard.information",
            compact(
                'resources',
                'totUser',
                'totActiveHotspot',
                'listInterface',
                'logs'
            )
        );
    }
    public function voucher()
    {

        $listPool = $listProfile = $listHotspot = [];

        $listData = [
            'listProfile' => ['/ip/hotspot/print'],
            'listHotspot' => ['/ip/hotspot/user/profile/print'],
            'listPool'    => ['/ip/pool/print']
        ];

        $listColor = [
            ["aqua", "green", "yellow", "red"],
            ["info", "success", "warning", "danger"]
        ];

        foreach ($listData as $key => $value) {
            $query = "";
            $query = (new Query($value[0]));
            $getData = count($value) >= 2 ? $this->client->qr($query, $value[1]) : $this->client->qr($query);

            switch ($key) {
                case 'listHotspot':
                    $iter = $no = 0;
                    foreach ($getData as $value) {
                        $iter++;
                        $name = $value["name"];

                        $query2 = (new Query("/ip/hotspot/user/print"));
                        $query2->equal("count-only");
                        $query2->where("profile", $name);
                        $total = $this->client->qr($query2);
                        $total = $total["after"]["ret"];
                        $total = $total > 1 ? "$total items" : "$total item";

                        $no = $no > 3 ? 0 : $no;
                        $listHotspot[$iter] = [
                            "id"    => $value[".id"],
                            "name" => $value["name"],
                            "total" => $total,
                            "bg-color" => "bg-" . $listColor[0][$no],
                            "btn-color" => "btn-" . $listColor[1][$no]
                        ];
                        $no++;
                    }
                    break;

                case 'listProfile':
                    $listProfile[] = "all";
                    foreach ($getData as $value) {
                        $listProfile[] = $value["name"];
                    }
                    break;
                    
                case 'listPool':
                    $listPool[] = "none";
                    foreach ($getData as $value) {
                        $listPool[] = $value["name"];
                    }
                    break;
            }
        }

        return view("mikrotik.dashboard.voucher", compact('listProfile', 'listHotspot', 'listPool'));
    }
}