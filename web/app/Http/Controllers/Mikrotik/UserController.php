<?php

namespace App\Http\Controllers\Mikrotik;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \RouterOS\Client;
use \RouterOS\Query;


class UserController extends Controller
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

    public function index($profile = "all")
    {
        $listProfile = $listHotspot = [];

        $listData = [
            'listProfile' => ['/ip/hotspot/print'],
            'listHotspot' => ['/ip/hotspot/user/profile/print']
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
                            "name" => $value["name"],
                            "total" => $total,
                        ];
                        $no++;
                    }
                    break;
            }
        }

        return view(
            'mikrotik.user',
            compact("profile", "listHotspot")
        );
    }

    public function userEdit(Request $request)
    {
        $data = $request->input("data");

        $query = (new Query("/ip/hotspot/user/set"));

        $name = $profile = "";
        foreach ($data as $v) {
            if (empty($v['value']))
                continue;

            if ($v["name"] == "name") {
                $name = $v["value"];
            }
            $query->equal($v['name'], $v['value']);
        }

        $query_check = (new Query("/ip/hotspot/user/print"));
        $query_check->where("name", $name);
        $query_check->equal("count-only");
        $check = $this->client->qr($query_check);

        // print_r($check);

        if (isset($check["after"]["ret"])) {
            if ($check["after"]["ret"] > 1) {
                return response()->json([
                    "status" => "error",
                    "title" => "Already Exists !",
                    "message" => "Item with this name already exists !",
                    "route" => \Request::route()->getName()
                ], 400);
            }
        }

        $result = $this->client->qr($query);

        if (!empty($result)) {
            return response()->json([
                "status" => "error",
                "title" => "Failed !",
                "message" => ucfirst($result['after']['message']),
                "route" => \Request::route()->getName()
            ], 400);
        } else {
            return response()->json([
                "status" => "success",
                "title" => "Success",
                "message" => "Your data has been updated."
            ], 200);
        }


    }
    public function userData(Request $request)
    {
        // dd($profile);
        $listHotspotUser = [];
        $query = (new Query("/ip/hotspot/user/print"));

        $profile = $request->input("profile");
        if ($profile != "all") {
            $query->where("profile", $profile);
        }
        foreach ($this->client->qr($query) as $value) {
            $listHotspotUser[] = [
                "id" => $value[".id"],
                "server" => isset($value["server"]) ? $value["server"] : "-",
                "name" => isset($value["name"]) ? $value["name"] : "-",
                "profile" => isset($value["profile"]) ? $value["profile"] : "-",
                "limit-uptime" => isset($value["limit-uptime"]) ? $value["limit-uptime"] : "",
                "limit-bytes-total" => isset($value["limit-bytes-total"]) ? $value["limit-bytes-total"] : "",
                "comment" => isset($value["comment"]) ? $value["comment"] : "-",
            ];
        }

        return $listHotspotUser;
    }

    public function userRemove(Request $request)
    {
        // dd($request->input());
        $id = $request->input('data');

        $query = (new Query("/ip/hotspot/user/remove"));
        $query->equal(".id", $id);

        $result = $this->client->qr($query);
        // dd($result);

        if (!empty($result)) {
            return response()->json([
                "status" => "error",
                "title" => "Failed !",
                "message" => ucfirst($result['after']['message']),
                "route" => \Request::route()->getName()
            ], 400);
        } else {
            return response()->json([
                "status" => "success",
                "title" => "Deleted",
                "message" => "Your data has been deleted."
            ], 200);
        }
    }

    public function generate(Request $request)
    {
        $count = $request->input("qty");
        $prefix = $request->input("prefix");
        $prefix = empty($prefix) ? "" : "$prefix-";
        $length = $request->input("length");
        $comment = $request->input("comment");

        $username = empty(Auth::user('name')) ? "-" : Auth::user()->name;

        $comment = date("d-M-Y H:i:s");

        $data = [];
        $count = 1;
        $failedQty = $successQty = 0;
        do {

            $name = "";
            do {
                $name = $prefix . generateRandomString($length);
                $query = "";
                $query = (new Query('/ip/hotspot/user/print'));
                $query->where("name", $name);
                $isExist = $this->client->q($query)->r();
            } while (count($isExist) > 0);

            $item = [
                "server" => $request->input("server"),
                "name" => $name,
                "password" => $name,
                "profile" => $request->input("profile"),
                "limit-uptime" => $request->input("limit-uptime"),
                // "limit-uptime" => "asd",
                "limit-bytes-total" => $request->input("limit-bytes-total"),
                "comment" => $comment,
            ];

            $query = "";
            $query = (new Query("/ip/hotspot/user/add"));
            foreach ($item as $key => $value) {
                $query->equal($key, $value);
            }

            $result = $this->client->qr($query);
            if (!isset($result['after']['ret'])) {
                $failedQty++;

                $msg = $result['after']['message'];

                return response()->json([
                    "status" => "error",
                    "route" => \Request::route()->getName(),
                    "message" => ucfirst("$msg. $successQty data(s) success to be created.")
                ], 400);
            } else {
                $successQty++;
            }

            $data[] = $result;
            $count--;
            // dd($result);
        } while ($count == 0);

        return response()->json([
            "status" => "success",
            "message" => "Success"
        ], 200);
    }

    public function profileAdd(Request $request)
    {
        $data = $request->input("data");
        $action = $request->input("action");

        if ($action == "update") {
            $query = (new Query("/ip/hotspot/user/profile/set"));
        } else {
            $query = (new Query("/ip/hotspot/user/profile/add"));
        }

        $name = "";
        foreach ($data as $v) {
            if (empty($v["value"]))
                continue;
            if ($v["name"] == "name") {
                $name = $v["value"];
            }

            $query->equal($v['name'], $v['value']);
        }

        $query_check = (new Query("/ip/hotspot/user/profile/print"));
        $query_check->where("name", $name);
        $query_check->equal("count-only");
        $check = $this->client->qr($query_check);

        if (isset($check["after"]["ret"])) {
            if ($check["after"]["ret"] > 1) {
                return response()->json([
                    "status" => "error",
                    "title" => "Already Exists !",
                    "message" => "Item with this name already exists !",
                    "route" => \Request::route()->getName()
                ], 400);
            }
        }
        $result = $this->client->qr($query);

        // dd($query);
        // dd($result);

        if (!isset($result["after"]["ret"]) && $action != "update") {
            return response()->json([
                "status" => "error",
                "title" => "Failed !",
                "message" => ucfirst($result['after']['message']),
                "route" => \Request::route()->getName()
            ], 400);
        } elseif (!empty($result) && $action == "update") {
            return response()->json([
                "status" => "error",
                "title" => "Failed !",
                "message" => ucfirst($result['after']['message']),
                "route" => \Request::route()->getName()
            ], 400);
        } else {
            return response()->json([
                "status" => "success",
                "title" => $action == "update" ? "Data updated" : "Data Added !",
                "message" => $action == "update" ? "Your data has been updated": "Your data has been added"
            ], 200);
        }
    }

    public function profileData(Request $request)
    {
        $id = $request->input("data");

        $query = (new Query("/ip/hotspot/user/profile/print"));
        $query->where(".id", $id);

        $result = $this->client->qr($query);
        // dd($query);
        // dd($result[0]);
        // dd($this->client->qr($query));
        return response()->json($result[0]);
    }
    public function profileRemove(Request $request)
    {
        $id = $request->input('data');

        $query = (new Query("/ip/hotspot/user/profile/remove"));
        $query->equal(".id", $id);

        $result = $this->client->qr($query);

        if (!empty($result)) {
            return response()->json([
                "status" => "error",
                "title" => "Failed !",
                "message" => ucfirst($result['after']['message']),
                "route" => \Request::route()->getName()
            ], 400);
        } else {
            return response()->json([
                "status" => "success",
                "title" => "Deleted",
                "message" => "Your data has been deleted."
            ], 200);
        }
    }

}