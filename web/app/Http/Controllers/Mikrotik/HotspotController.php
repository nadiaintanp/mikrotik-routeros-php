<?php

namespace App\Http\Controllers\Mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \RouterOS\Client;
use \RouterOS\Query;

class HotspotController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
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

    public function index( $profile = "all")
    {
        $listHotspotUser = [];
        $query  = (new Query("/ip/hotspot/user/print"));
        
        if ($profile != "all") {
            $query->where("profile", $profile);
        }
        foreach ($this->client->qr($query) as $value) {
            $listHotspotUser[] = [
                "id"                => $value[".id"],
                "server"            => isset($value["server"]) ? $value["server"] : "-",
                "name"              => isset($value["name"]) ? $value["name"] : "-",
                "profile"           => isset($value["profile"]) ? $value["profile"] : "-",
                "limit-uptime"      => isset($value["limit-uptime"]) ? $value["limit-uptime"] : "",
                "limit-bytes-total" => isset($value["limit-bytes-total"]) ? $value["limit-bytes-total"] : "",
                "comment"           => isset($value["comment"]) ? $value["comment"] : "-",
            ];
        }
        // dd($listHotspotUser);
        return view(
            'mikrotik.hotspot',
            compact(
                'listHotspotUser'
            )
        );
    }
    
}
