@extends('layouts.AdminLTE.index')
@section('icon_page', 'wifi')
@section('title', 'Hotspot')
@section('menu_pagina')
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table id="tbHotspotUser" class="display" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            var listHotspotUser = {!! json_encode($listHotspotUser) !!};

            function formatBytes(bytes, decimals = 2) {
                if (!+bytes) return '0 Bytes'

                const k = 1024
                const dm = decimals < 0 ? 0 : decimals
                const sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']

                const i = Math.floor(Math.log(bytes) / Math.log(k))

                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
            }

            $("#tbHotspotUser").DataTable({
                data: listHotspotUser,
                columns: [{
                        title: "ID",
                        data: "id",
                        visible: false,
                        searchable: false
                    },
                    {
                        title: "Server",
                        data: "server",
                        defaultContent: "-"
                    },
                    {
                        title: "Name",
                        data: "name",
                        defaultContent: "-"
                    },
                    {
                        title: "Profile",
                        data: "profile",
                        defaultContent: "-"
                    },
                    {
                        title: "Expired",
                        data: "limit-uptime",
                        defaultContent: "-"
                    },
                    {
                        title: "Limit Bytes",
                        data: "limit-bytes-total",
                        defaultContent: "-",
                        render: function(data) {
                            return formatBytes(data);
                        }
                    },
                    {
                        title: "Comment",
                        data: "comment",
                        defaultContent: "-"
                    },
                ]
            });
        });
    </script>
@endsection
