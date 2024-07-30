@extends('layouts.AdminLTE.index')
@section('icon_page', 'dashboard')
{{-- @section('title', 'Dashboard ') --}}
@section('menu_pagina')
@section('content')
    <div class="nav-tabs-custom" style="background: none !important; box-shadow: none !important;">
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header"><i class="fa fa-dashboard"></i> Dashboard</li>
            <li class="active"><a href="#tabsBoardInformation" data-toggle="tab">Board Information</a></li>
            <li><a href="#tabsVoucher" data-toggle="tab">Quick Voucher</a></li>
        </ul>
        <div class="tab-content" style="background: none !important; padding: 40px 10px">
            {{-- bagian board information --}}
            <div class="tab-pane active" id="tabsBoardInformation">
                <div class="row">
                    {{-- Board Information --}}
                    <div class="col-lg-12">
                        <div class="row">
                            <!-- system date & time -->
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-black">
                                    <div class="inner">
                                        <h4>System date & time</h4>
                                        <p>{{ date('d-M-Y H:i:s e') }}</p>
                                        <p>Uptime: {{ $resources['uptime'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- board information  -->
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-black">
                                    <div class="inner">
                                        <h4>Board Information</h4>
                                        <p>
                                            Board Name: {{ $resources['board-name'] }}<br>
                                            Model: {{ $resources['model'] }}<br>
                                            RouterOS: {{ $resources['version'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- resource -->
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-black">
                                    <div class="inner">
                                        <h4>Board Resource</h4>
                                        <p>
                                            CPU Load: {{ $resources['cpu-load'] }}<br>
                                            Free Memory: {{ $resources['free-memory'] }}<br>
                                            Free HDD: {{ $resources['free-hdd-space'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hotspot Information --}}
                    <div class="col-lg-7">
                        <div class="row">
                            {{-- Total active user --}}
                            <div class="col-lg-6 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>{{ $totUser }}</h3>
                                        <p>Total User</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- total active hotspot --}}
                            <div class="col-lg-6 col-xs-6">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>{{ $totActiveHotspot }}</h3>
                                        <p>Total Active Hotspot</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-pie-graph"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- grafik traffic --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs pull-right">
                                        <li class="pull-left header"><i class="fa fa-inbox"></i>Traffic</li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="row">
                                            <div class="col-lg-3 pull-right">
                                                <select id="btTrafficInterface" class="form-control">
                                                    @foreach ($listInterface as $item)
                                                        <option>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-12">
                                                <canvas id="traffic"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Log --}}
                    <div class="col-lg-5">
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="ion ion-clipboard"></i>
                                <h3 class="box-title">Logs</h3>
                            </div>
                            <div class="box-body">
                                <table id="tbMikrotikLog" class="display" width="100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- bagian voucher --}}
            <div class="tab-pane" id="tabsVoucher">
                <div class="row">
                    @foreach ($listHotspot as $item)
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box {{ $item['bg-color'] }} d-flex">
                                <span class="info-box-icon d-flex"><i class="fa fa-bookmark-o"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Profile : <b>{{ $item['name'] }}</b></span>
                                    <span class="info-box-number">{{ $item['total'] }}</span>

                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <div class="btn-group">
                                            <form action="{{ route('hotspot.user.list', ['profile' => $item['name']]) }}"
                                                method="get">
                                                <button type="submit" class="act-view-more btn {{ $item['btn-color'] }}"
                                                    style="border: none !important">
                                                    <i class="fa fa-list"></i> View More
                                                </button>
                                            </form>
                                            <button type="button" class="act-generate btn {{ $item['btn-color'] }}"
                                                style="border: none !important" value="{{ $item['name'] }}"
                                                data-toggle="modal" data-target="#modal-generate">
                                                <i class="fa fa-plus"></i> Generate
                                            </button>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form class="form-horizontal" id="fGenerateUser">
                    <div class="modal fade" id="modal-generate">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Generate User</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="box-body">
                                        {{-- qty --}}
                                        <div class="form-group">
                                            <label for="fGenerateQty" class="col-sm-3 control-label">Qty</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="fGenerateQty"
                                                    name="qty" placeholder="Default: 1">
                                            </div>
                                        </div>
                                        {{-- server --}}
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Server</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="server" id="fGenerateServer">
                                                    @foreach ($listProfile as $item)
                                                        <option>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- name length --}}
                                        <div class="form-group">
                                            <label for="fGenerateLength" class="col-sm-3 control-label">Name
                                                Length</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="fGenerateLength"
                                                    name="length" placeholder="Default: 4">
                                            </div>
                                        </div>
                                        {{-- Prefix --}}
                                        <div class="form-group">
                                            <label for="fGeneratePrefix" class="col-sm-3 control-label">Prefix</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="fGeneratePrefix"
                                                    name="prefix" placeholder="Default: user">
                                            </div>
                                        </div>
                                        {{-- profile --}}
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Profile</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="profile" id="fGenerateProfile">
                                                    @foreach ($listHotspot as $item)
                                                        @php
                                                            if ($item['name'] == 'all') {
                                                                continue;
                                                            }
                                                        @endphp
                                                        <option>{{ $item['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- time limit --}}
                                        <div class="form-group">
                                            <label for="fGenerateTimeLimit" class="col-sm-3 control-label">Time
                                                Limit</label>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fGenerateLimitDay"
                                                    name="limit-uptime-day" placeholder="Day">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fGenerateLimitHour"
                                                    name="limit-uptime-hour" placeholder="Hour">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fGenerateLimitMinutes"
                                                    name="limit-uptime-minutes" placeholder="Minutes">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="number" class="form-control" id="fGenerateLimitSeconds"
                                                    name="limit-uptime-seconds" placeholder="Seconds">
                                            </div>
                                        </div>
                                        {{-- data limit --}}
                                        <div class="form-group">
                                            <label for="fGenerateDataLimit" class="col-sm-3 control-label">Data
                                                Limit</label>

                                            <div class="col-sm-9">
                                                <div class="input-group input-group">
                                                    <input type="number" class="form-control" id="fGenerateDataLimit"
                                                        name="limit-bytes-total" placeholder="Data Limit">
                                                    <div class="input-group-btn">
                                                        <select name="data-size" id="fGenerateDataSize"
                                                            class="btn btn-info dropdown-toggle">
                                                            <option value="1048576">MB</option>
                                                            <option value="1073741824">GB</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        {{-- comment --}}
                                        <div class="form-group">
                                            <label for="fGenerateComment" class="col-sm-3 control-label">Comment</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" rows="3" id="fGenerateComment" name="comment"
                                                    placeholder="Default : Generated on dd-mm-yyyy hh:ii:ss by current user"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"
                                        id="doCancel">Close</button>
                                    <button type="button" class="btn btn-primary" id="doGenerateUser">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {

            var request;

            var passInput = $("#fUserPassword");
            if ($("#enable-show").is(":checked") && passInput.attr("type") === 'password') {
                passInput.attr('type', 'text');
            }
            // toggle password
            $('#enable-show').on('click', function() {
                if (passInput.attr('type') === 'password') {
                    passInput.attr('type', 'text');
                } else {
                    passInput.attr('type', 'password');
                }
            })

            // show log
            // var listLog = {!! json_encode($logs) !!};
            // $('#tbMikrotikLog').DataTable({
            //     data: listLog,
            //     order: [
            //         [0, "desc"]
            //     ],
            //     columns: [{
            //             title: "Time",
            //             data: "time",
            //             order: "desc"
            //         },
            //         {
            //             title: "User",
            //             data: "user"
            //         },
            //         {
            //             title: "Message",
            //             data: "message"
            //         },
            //     ]
            // });

            // traffic

            // function formatBytes(bytes, decimals = 2) {
            //     if (!+bytes) return '0 Bytes'

            //     const k = 1024
            //     const dm = decimals < 0 ? 0 : decimals
            //     const sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']

            //     const i = Math.floor(Math.log(bytes) / Math.log(k))
            //     if (i < 0)
            //         return `${bytes} ${sizes[0]}`;

            //     return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
            // }

            // // console.log("updating traffic graph");
            // const ctx = document.getElementById('traffic');
            // var graphTraffic = new Chart(ctx, {
            //     type: 'line',
            //     data: {
            //         labels: [],
            //         datasets: [{
            //             label: 'No Data',
            //             data: [],
            //             borderWidth: 1
            //         }]
            //     },
            //     options: {
            //         plugins: {
            //             title: {
            //                 display: true,
            //             }
            //         },
            //         scales: {
            //             y: {
            //                 type: 'linear',
            //                 display: true,
            //                 position: 'left',
            //                 beginAtZero: true,
            //                 ticks: {
            //                     callback: function(label, index, labels) {
            //                         return formatBytes(label);
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // });

            // function getGraphTraffic(interface) {
            //     if (request != null) {
            //         request.abort();
            //         request = null;
            //     }

            //     $.ajax({
            //         url: "{{ route('traffic.monitor.list') }}",
            //         type: "GET",
            //         dataType: "json",
            //         data: {
            //             "_token": "{{ csrf_token() }}",
            //             "name": interface
            //         },
            //         success: function(resp) {

            //             var output = {
            //                 labels: resp.labels,
            //                 datasets: resp.datasets
            //             };

            //             graphTraffic.data = resp;
            //             graphTraffic.update();
            //         }
            //     });
            // }
            // var getInterface = $("#btTrafficInterface").val();
            // getGraphTraffic(getInterface);
            // $("#btTrafficInterface").on("change", function() {
            //     getInterface = $("#btTrafficInterface").val();
            //     getGraphTraffic(getInterface)
            // });

            // window.setInterval(function() {
            //     console.log('live reload traffic ....');
            //     getInterface = $("#btTrafficInterface").val();
            //     getGraphTraffic(getInterface)
            // }, 30000);

            // voucher
            $(".act-generate").on("click", function() {
                var optProfile = $(this).val();
                if (optProfile != "all") {
                    $("#fGenerateProfile").empty()
                        .append(
                            $("<option></option>")
                            .attr("value", optProfile)
                            .text(optProfile)
                        ).attr('disabled', 'disabled');
                }
            });

            $("#doGenerateUser").on("click", function(e) {
                e.preventDefault();
                console.log("generating user hotspot");

                var url = "{{ route('hotspot.user.add') }}";

                var limitDay = $('#fGenerateLimitDay').val() || 0;
                var limitHour = $('#fGenerateLimitHour').val() || 00;
                var limitMin = $("#fGenerateLimitMin").val() || 00;
                var limitSec = $("#fGenerateLimitSec").val() || 00;

                var limitUptime = limitDay + "d " + limitHour + ":" + limitMin + ":" + limitSec;

                if (request != null) {
                    request.abort();
                    request = null;
                }

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "qty": $("#fGenerateQty").val() || 1,
                        "server": $("#fGenerateServer").val(),
                        "length": $("#fGenerateLength").val() || 4,
                        "prefix": $("#fGeneratePrefix").val() || "user",
                        "profile": $("#fGenerateProfile").val(),
                        "limit-uptime": limitUptime == "0d 00:00:00" ? 0 : limitUptime,
                        "limit-bytes-total": $("#fGenerateDataLimit").val() * $(
                            "#fGenerateDataSize").val(),
                        "comment": $("#fGenerateComment").val(),
                    }
                });
            });
        });
    </script>
@endsection
