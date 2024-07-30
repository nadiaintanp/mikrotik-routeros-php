<?php

namespace App\Http\Controllers\Mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \RouterOS\Client;
use \RouterOS\Query;

class LogController extends Controller
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

    public function index(Request $request)
    {
        // abort(404);
        return view("mikrotik.logs.home");
    }

    public function view($type = "hotspot")
    {
        // dd("asdasd");
        $loadPage = "";
        switch ($type) {
            case 'hotspot':
                // dd("asd");
                $query = "";
                $query = (new Query('/log/print'));
                $getData = $this->client->qr($query, ["topics", "hotspot,info,debug"]);

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

                return view("mikrotik/logs/hotspot", compact('logs'));
                break;

            case 'user':
                $loadPage = "mikrotik.logs.user";
                break;

            default:
                abort(404);
                break;
        }
        return view($loadPage);
    }

}