<?php

namespace App\Http\Controllers\Mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \RouterOS\Client;
use \RouterOS\Query;

class SystemController extends Controller
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

    public function scheduler(Request $request)
    {
        $query = (new Query("/system/scheduler/print"));
        $temp = $this->client->qr($query);
        // dd($temp);

        // echo gettype($temp[0]["start-date"]);
        // dd($temp);

        $data = [];
        foreach ($temp as $key => $value) {
            list($mm, $dd, $yy) = explode("/", $value["start-date"]);
            $time = isset($value["start-time"]) ? $value["start-time"] : "00:00:00";
            $dt = ucfirst("$dd $mm $yy $time");
            // echo date("d F Y", strtotime("$dd $mm $yy"));
            // exit;

            $data[] = [
                "id" => isset($value[".id"]) ? $value[".id"] : "-",
                "name" => isset($value["name"]) ? $value["name"] : "-",
                "event" => isset($value["on-event"]) ? $value["on-event"] : "-",
                "start" => isset($value["start-date"]) ? date("d F Y H:i:s", strtotime(ucfirst("$dd $mm $yy $time"))) : "-",
                "interval" => isset($value["interval"]) ? $value["interval"] : "-",
                "next-run" => isset($value["next-run"]) ? $value["next-run"] : "-",
                "run-count" => isset($value["run-count"]) ? $value["run-count"] : "-"
            ];
        }

        return view("mikrotik.system.scheduler", compact("data"));
    }

    public function schedulerData(Request $request)
    {
        $query = (new Query("/system/scheduler/print"));
        $temp = $this->client->qr($query);
        // dd($temp);

        // echo gettype($temp[0]["start-date"]);
        // dd($temp);

        $data = [];
        foreach ($temp as $key => $value) {
            list($mm, $dd, $yy) = explode("/", $value["start-date"]);
            $time = isset($value["start-time"]) ? $value["start-time"] : "00:00:00";
            $dt = ucfirst("$dd $mm $yy $time");

            $data[] = [
                "id" => isset($value[".id"]) ? $value[".id"] : "-",
                "name" => isset($value["name"]) ? $value["name"] : "-",
                "event" => isset($value["on-event"]) ? $value["on-event"] : "-",
                "start" => isset($value["start-date"]) ? date("d F Y H:i:s", strtotime(ucfirst("$dd $mm $yy $time"))) : "-",
                "interval" => isset($value["interval"]) ? $value["interval"] : "-",
                "next-run" => isset($value["next-run"]) ? $value["next-run"] : "-",
                "run-count" => isset($value["run-count"]) ? $value["run-count"] : "-"
            ];
        }

        return $data;
    }

    public function schedulerAdd(Request $request)
    {
        $data = $request->input('data');
        $is_edit = $request->input("is_edit") == "false" ? false : true;

        if ($is_edit) {
            $query = (new Query("/system/scheduler/set"));
            $name = "";
            foreach ($data as $v) {
                if (empty($v['value']))
                    continue;
                
                if ($v["name"] == "name") {
                    $name = $v["value"];
                }
                $query->equal($v['name'], $v['value']);
            }

            $query_check = (new Query("/system/scheduler/print"));
            $query_check->where("name", $name);
            $query_check->equal("count-only");
            $check = $this->client->qr($query_check);

            
            // dd($check);
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
                    "title" => $is_edit ? "Data Edited !" : "Data Added !",
                    "message" => $is_edit ? "Your data has been edited." : "Your data has been added"
                ], 200);
            }

        } else {
            $query = (new Query("/system/scheduler/add"));
            foreach ($data as $v) {
                if (empty($v['value']))
                    continue;
                $query->equal($v['name'], $v['value']);
            }

            $result = $this->client->qr($query);

            if (!isset($result['after']['ret'])) {
                return response()->json([
                    "status" => "error",
                    "title" => "Failed !",
                    "message" => ucfirst($result['after']['message']),
                    "route" => \Request::route()->getName()
                ], 400);
            } else {
                return response()->json([
                    "status" => "success",
                    "title" => $is_edit ? "Data Edited !" : "Data Added !",
                    "message" => $is_edit ? "Your data has been edited." : "Your data has been added"
                ], 200);
            }
        }
    }

    public function schedulerRemove(Request $request)
    {
        $id = $request->input('data');
        // $id = "*1";

        $query = (new Query("/system/scheduler/remove"));
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
}