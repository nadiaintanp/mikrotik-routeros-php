@extends('layouts.AdminLTE.index')
@section('icon_page', 'users')
@section('title', 'User')
@section('menu_pagina')
@section('content')
    <div class="nav-tabs-custom" style="background: none !important; box-shadow: none !important;">
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header">
                <i class="fa fa-users"></i> User
            </li>
        </ul>
    </div>

    <div class="row p-5">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="col-sm-6 text-left">
                        <h3 class="box-title">User List</h3>
                    </div>
                </div>
                <div class="box-body">
                    <table id="tbHotspotUser" class="display" width="100%"></table>
                </div>
            </div>
        </div>
    </div>

    <form class="form-horizontal" id="form-user-edit">
        <div class="modal fade" id="modal-user-edit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            {{-- name --}}
                            <div class="form-group">
                                <label for="form-user-name" class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="hidden" id="form-user-id" name=".id" value="">
                                    <input type="text" class="form-control" id="form-user-name" name="name"
                                        placeholder="User name goes here">
                                </div>
                            </div>
                            {{-- profile --}}
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Profile</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="profile" id="form-user-profile">
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
                                <label for="form-user-limit-uptime" class="col-sm-3 control-label">Time
                                    Limit</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="form-user-limit-uptime"
                                        name="limit-uptime" placeholder="limit-uptime">
                                </div>
                            </div>
                            {{-- data limit --}}
                            <div class="form-group">
                                <label for="form-user-limit-bytes-total" class="col-sm-3 control-label">Data
                                    Limit</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="form-user-limit-bytes-total"
                                        name="limit-bytes-total" placeholder="limit-bytes-total">
                                </div>

                                {{-- <div class="col-sm-9">
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
                                </div> --}}

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="act-user-save">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            var listHotspotUser = {!! json_encode($listHotspotUser ?? '') !!};

            function formatBytes(bytes, decimals = 2) {
                if (!+bytes) return '0 Bytes'

                const k = 1024
                const dm = decimals < 0 ? 0 : decimals
                const sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']

                const i = Math.floor(Math.log(bytes) / Math.log(k))

                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
            }

            var url_user_data = "{{ route('hotspot.user.data') }}";
            var tbHotspotUser = $("#tbHotspotUser").DataTable({
                // data: listHotspotUser,
                ajax: {
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "profile": "{{ $profile }}"
                    },
                    url: url_user_data,
                    method: "POST",
                    dataSrc: ""
                },
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
                        defaultContent: "-",
                        className: 'dt-body-center'
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
                    {
                        title: "Action",
                        data: null,
                        className: 'dt-body-center',
                        orderable: false,
                        searchable: false,
                        defaultContent: '<div class="btn-group">' +
                            '<button type="button" class="btn btn-danger act-user-del">' +
                            '<i class="fa fa-trash"></i>' +
                            '</button>' +
                            '<button type="button" class="btn btn-info act-user-edit">' +
                            '<i class="fa fa-pencil"></i>' +
                            '</button>' +
                            '</div>'
                    }
                ]
            });

            $("#tbHotspotUser tbody").on("click", ".act-user-del", function() {
                var data = tbHotspotUser.row($(this).parents('tr')).data();
                var data_id = data["id"];

                Swal.fire({
                    title: `Are you sure to delete <span style="color: #d73925 !important;">${data['name']}</span> ?`,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url_user_del = "{{ route('hotspot.user.delete') }}";
                        $.ajax({
                            url: url_user_del,
                            type: "POST",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "data": data_id
                            },
                            success: function(_) {
                                Swal.fire(
                                    _.title,
                                    _.message,
                                    _.status
                                );

                                tbHotspotUser.ajax.reload();
                            },
                            error: function(_) {
                                var __ = _.responseJSON
                                Swal.fire(
                                    __.title,
                                    __.message,
                                    __.status
                                );
                            }
                        });
                    }
                })
            });

            function clearForm() {
                $("#form-user-id").val("");
                $("#form-user-name").val("");
                $("#form-user-profile").val("default");
                $("#form-user-limit-uptime").val("");
                $("#form-user-limit-bytes-total").val("");
            }

            $("#tbHotspotUser tbody").on("click", ".act-user-edit", function() {
                var data = tbHotspotUser.row($(this).parents('tr')).data();
                
                // preparation
                clearForm();
                $("#form-user-id").val(data["id"]);
                $("#form-user-name").val(data["name"]);
                $("#form-user-profile").val(data['profile']);
                $("#form-user-limit-uptime").val(data['limit-uptime']);
                $("#form-user-limit-bytes-total").val(data['limit-bytes-total']);

                $("#modal-user-edit").modal("toggle");
            });

            $("#act-user-save").on("click", function (e) {
                e.preventDefault();
                
                var data = $("#form-user-edit").serializeArray();
                var url_user_edit = "{{ route('hotspot.user.edit') }}";
                $.ajax({
                    url: url_user_edit,
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "data": data
                    },
                    success: function (_) {
                        $("#modal-user-edit").modal("toggle");

                        Swal.fire(
                            _.title,
                            _.message,
                            _.status
                        );

                        tbHotspotUser.ajax.reload();
                    },
                    error: function(_) {
                        var __ = _.responseJSON
                        Swal.fire(
                            __.title,
                            __.message,
                            __.status
                        );
                    }
                });

            });
        });
    </script>
@endsection
