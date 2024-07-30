@extends('layouts.AdminLTE.index')
@section('icon_page', 'clock-o')
@section('title', 'Scheduler ')
@section('menu_pagina')
@section('content')
    <div class="nav-tabs-custom" style="background: none !important; box-shadow: none !important;">
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header">
                <i class="fa fa-clock-o"></i> Scheduler
            </li>
            <li class="pull-right">
                <button type="button" class="btn btn-app" id="btn-gen-sch">
                    <i class="fa fa-edit"></i> Generate Default Scheduler
                </button>
                <button type="button" class="btn btn-app" data-toggle="modal" data-target="#modal-scheduler"
                    id="btn-add-sch">
                    <i class="fa fa-edit"></i> Add Scheduler
                </button>
            </li>
        </ul>
    </div>
    <div class="row p-5">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="col-sm-6 text-left">
                        <h3 class="box-title">Scheduler List</h3>
                    </div>
                </div>
                <div class="box-body">
                    <table id="tbScheduler" class="table table-hover" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-scheduler">
        <div class="modal-dialog modal-lg">
            <div class="modal-body">
                <div class="box box-primary" style="padding:3rem !important">
                    <div class="box-header with-border">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h3 class="box-title">Form Scheduler</h3>
                    </div>
                    <form role="form" id="form-modal-scheduler">
                        <div class="box-body">
                            <div class="form-group">
                                <input type="hidden" class="form-control" name=".id" id="form-scheduler-id">
                            </div>
                            <div class="form-group">
                                <label for="form-scheduler-name">Name</label>
                                <input type="text" class="form-control" name="name" id="form-scheduler-name"
                                    placeholder="Name of the task">
                            </div>
                            <div class="form-group">
                                <label for="form-scheduler-event">On Event</label>
                                <input type="text" class="form-control" name="on-event" id="form-scheduler-event"
                                    placeholder="Your script goes here">
                                <small class="help-block">Name of the script to execute. It must be presented at /system
                                    script.
                                </small>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="form-scheduler-interval">Interval</label>
                                        <input type="numeric" name="interval" class="form-control"
                                            id="form-scheduler-interval" placeholder="add interval each run">
                                        <small class="help-block">Default <b>0s</b></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var listScheduler = {!! json_encode($data) !!};

            $("#btn-add-sch").on("click", function() {
                $("#form-scheduler-id").val("");
                $("#form-modal-scheduler").reset();
            });

            var url_scheduler_data = "{{ route('scheduler.data') }}"
            var table = $('#tbScheduler').DataTable({
                // data: listScheduler,
                ajax: {
                    url: url_scheduler_data,
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataSrc: ""
                },
                columns: [{
                    title: "ID",
                    data: "id",
                    visible: false,
                    searchable: false,
                    orderable: false,
                    defaultContent: "-"
                }, {
                    title: "Name",
                    data: "name",
                    defaultContent: "-"
                }, {
                    title: "Event",
                    data: "event",
                    defaultContent: "-"
                }, {
                    title: "Start",
                    data: "start",
                    className: "dt-center",
                    defaultContent: "-"
                }, {
                    title: "Interval",
                    data: "interval",
                    className: "dt-center",
                    defaultContent: "-"
                }, {
                    title: "Next Run",
                    data: "next-run",
                    className: "dt-center",
                    defaultContent: "-"
                }, {
                    title: "Run Count",
                    data: "run-count",
                    className: "dt-center",
                    defaultContent: "-"
                }, {
                    title: "Action",
                    data: null,
                    className: "dt-center",
                    orderable: false,
                    searchable: false,
                    defaultContent: '<div class="btn-group">' +
                        '<button type="button" class="btn btn-danger act-sch-del">' +
                        '<i class="fa fa-trash"></i>' +
                        '</button>' +
                        '<button type="button" class="btn btn-info act-sch-edit">' +
                        '<i class="fa fa-pencil"></i>' +
                        '</button>' +
                        '</div>'
                }]
            });

            function add_scheduler(data, is_form = false, is_edit = false) {
                var url = "{{ route('scheduler.add') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "data": data,
                        "is_edit": is_edit
                    },
                    success: function(_) {
                        if (is_form == true) {
                            $("#modal-scheduler").modal("toggle");
                        }

                        Swal.fire(
                            _.title,
                            _.message,
                            _.status
                        );

                        table.ajax.reload();
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

            $("#btn-gen-sch").on("click", function(e) {
                $("#form-scheduler-id").val("");
                $("#form-modal-scheduler").reset();

                var formdata = [{
                        name: "name",
                        value: "{{ env('SCHEDULER_TRAFFIC_NAME') }}"
                    },
                    {
                        name: "on-event",
                        value: "{{ env('SCHEDULER_TRAFFIC_EVENT') }}"
                    },
                    {
                        name: "interval",
                        value: "{{ env('SCHEDULER_TRAFFIC_INTERVAL') }}"
                    }
                ];
                add_scheduler(formdata, false, false);
            });

            $("#form-modal-scheduler").on("submit", function(e) {
                e.preventDefault();
                var formdata = $(this).serializeArray();

                var is_edit = $("#form-scheduler-id").val() == "" ? false : true;
                add_scheduler(formdata, true, is_edit);
            });

            // delete scheduler
            $("#tbScheduler tbody").on("click", ".act-sch-del", function(e) {
                var data = table.row($(this).parents('tr')).data();
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

                        var url_scheduler_del = "{{ route('scheduler.delete') }}";
                        $.ajax({
                            url: url_scheduler_del,
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

                                table.ajax.reload();
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
            // end delete scheduler

            // edit scheduler
            $("#tbScheduler tbody").on("click", ".act-sch-edit", function(e) {
                var data = table.row($(this).parents('tr')).data();

                $("#form-scheduler-id").val("");
                $("#form-modal-scheduler").reset();

                $("#form-scheduler-id").val(data['id']);
                $("#form-scheduler-name").val(data['name']);
                $("#form-scheduler-event").val(data['event']);
                $("#form-scheduler-interval").val(data['interval']);

                $("#modal-scheduler").modal("toggle");
                // Swal.fire({
                //     title: `Are you sure to delete <span style="color: #d73925 !important;">${data['name']}</span> ?`,
                //     text: "You won't be able to revert this!",
                //     icon: 'warning',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'Yes, delete it!'
                // }).then((result) => {
                //     if (result.isConfirmed) {

                //         var url_scheduler_del = "{{ route('scheduler.delete') }}";
                //         $.ajax({
                //             url: url_scheduler_del,
                //             type: "POST",
                //             data: {
                //                 "_token": "{{ csrf_token() }}",
                //                 "data": data_id
                //             },
                //             success: function(_) {
                //                 Swal.fire(
                //                     _.title,
                //                     _.message,
                //                     _.status
                //                 );

                //                 table.ajax.reload();
                //             },
                //             error: function(_) {
                //                 var __ = _.responseJSON
                //                 Swal.fire(
                //                     __.title,
                //                     __.message,
                //                     __.status
                //                 );
                //             }
                //         });
                //     }
                // })
            });
            // end edit scheduler
        });
    </script>
@endsection
