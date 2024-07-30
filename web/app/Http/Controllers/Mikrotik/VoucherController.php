<?php

namespace App\Http\Controllers\Mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use \RouterOS\Client;
use \RouterOS\Query;

class VoucherController extends Controller
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

    public function index()
    {

        $listQuery = [
            "profile"   => "/ip/hotspot/user/profile/print",
            "server"    => "/ip/hotspot/print"
        ];

        $listColor = [
            ["aqua","green","yellow","red"],
            ["info","success","warning","danger"]
        ];

        $no = 0;
        
        $formdata = $list = $data= [];
        
        foreach ($listQuery as $key => $value) {
            $query = "";
            $query = (new Query($value));
            $data[$key] = $this->client->qr($query);
        }

        foreach ($data["profile"] as $key => $value) {
            $name = $value["name"];

            $query2 = (new Query("/ip/hotspot/user/print"));
            $query2->equal("count-only");
            $query2->where("profile", $name);
            $total = $this->client->qr($query2);
            $total = $total["after"]["ret"];
            $total = $total > 1 ? "$total items" : "$total item";

            $no = $no > 3 ? 0 : $no;
            $list[] = [
                "name" => $value["name"],
                "total" => $total,
                "bg-color" => $listColor[0][$no],
                "btn-color" => $listColor[1][$no]
            ];
            $no++;
        }

        // dd($data["server"]);
        $formdata["server"][] = "all";
        foreach ($data["server"] as $key => $value) {
            $formdata["server"][] = $value["name"];
        }

        return view("mikrotik/voucher", compact("list", "formdata"));
    }
}