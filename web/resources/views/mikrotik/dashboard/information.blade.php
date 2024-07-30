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
                        <p>Uptime: {{ $resources['uptime'] ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- board information  -->
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-black">
                    <div class="inner">
                        <h4>Board Information</h4>
                        <p>
                            Board Name: {{ $resources['board-name'] ?? '-' }}<br>
                            Model: {{ $resources['model'] ?? '-' }}<br>
                            RouterOS: {{ $resources['version'] ?? '-' }}
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
                            CPU Load: {{ $resources['cpu-load'] ?? '-' }}<br>
                            Free Memory: {{ $resources['free-memory'] ?? '-' }}<br>
                            Free HDD: {{ $resources['free-hdd-space'] ?? '-' }}
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
                        <h3>{{ $totUser ?? 0 }}</h3>
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
                        <h3>{{ $totActiveHotspot ?? 0 }}</h3>
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

<script type="text/javascript">
    $(document).ready(function() {
        // -- Logs --
        var listLog = {!! json_encode($logs) !!};
        $('#tbMikrotikLog').DataTable({
            data: listLog,
            order: [
                [0, "desc"]
            ],
            columns: [{
                    title: "Time",
                    data: "time",
                    order: "desc"
                },
                {
                    title: "User",
                    data: "user"
                },
                {
                    title: "Message",
                    data: "message"
                },
            ]
        });
        // .. Logs ..

        // -- Traffic --
        var request;

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

        const ctx = document.getElementById('traffic');
        var graphTraffic = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'No Data',
                    data: [],
                    borderWidth: 1
                }]
            },
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
                            callback: function(label, index, labels) {
                                return formatBytes(label);
                            }
                        }
                    }
                }
            }
        });

        function getGraphTraffic(interface) {
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

                    var output = {
                        labels: resp.labels,
                        datasets: resp.datasets
                    };

                    graphTraffic.data = resp;
                    graphTraffic.update();
                }
            });
        }
        var getInterface = $("#btTrafficInterface").val();
        getGraphTraffic(getInterface);
        $("#btTrafficInterface").on("change", function() {
            getInterface = $("#btTrafficInterface").val();
            getGraphTraffic(getInterface)
        });

        window.setInterval(function() {
            console.log('live reload traffic ....');
            getInterface = $("#btTrafficInterface").val();
            getGraphTraffic(getInterface)
        }, {{ env('TRAFFIC_REFRESH', 30000) }});
        // .. Traffic ..
    });
</script>
