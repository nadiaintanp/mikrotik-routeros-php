@extends('layouts.AdminLTE.index')
@section('icon_page', 'line-chart')
@section('title', 'Monitoring Traffic')
@section('menu_pagina')
@section('content')
    <div class="nav-tabs-custom" style="background: none !important; box-shadow: none !important;">
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header">
                <i class="fa fa-line-chart"></i> Monitoring Traffic
            </li>
        </ul>
    </div>

    <div class="row">
        @foreach ($listInterface ?? [] as $item)
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-signal"></i> Traffic <b>{{ $item }}</b></li>
                        </h3>
                    </div>
                    <div class="box-body">
                        <canvas class="graph-traffic" id="traffic-{{ $item }}"></canvas>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var request;
            var myChart;

            function formatBytes(bytes, decimals = 2) {
                if (!+bytes) return '0 Bytes'

                const k = 1024
                const dm = decimals < 0 ? 0 : decimals
                const sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']

                const i = Math.floor(Math.log(bytes) / Math.log(k))
                if (i < 0)
                    return `${bytes} ${sizes[0]}`;

                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
            }

            function allGraphTraffic() {
                var list = {!! json_encode($listInterface) !!};

                $.each(list, function(idx, interface) {

                    if (request != null) {
                        request.abort();
                        request = null;
                    }

                    $.ajax({
                        url: "{{ route('traffic.monitor.list') }}",
                        type: "GET",
                        dataType: "json",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "name": interface
                        },
                        success: function(resp) {

                            var ctx = $(`#traffic-${interface}`);

                            var output = {
                                labels: resp.labels,
                                datasets: resp.datasets
                            };

                            var chartStatus = Chart.getChart(ctx);
                            if (chartStatus != undefined) {
                                chartStatus.destroy();
                            }

                            myChart = new Chart(ctx, {
                                type: 'line',
                                data: resp,
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                        }
                                    },
                                    scales: {
                                        y: {
                                            type: 'linear',
                                            display: true,
                                            position: 'left',
                                            beginAtZero: true,
                                            ticks: {
                                                callback: function(label, index,
                                                    labels) {
                                                    return formatBytes(label);
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    });

                });
            }

            allGraphTraffic();

            window.setInterval(function() {
                console.log('live reload traffic ....');
                allGraphTraffic()
            }, {{ env('TRAFFIC_REFRESH', 30000) }});
        });
    </script>
@endsection
