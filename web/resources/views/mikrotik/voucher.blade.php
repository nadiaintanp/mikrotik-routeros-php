@extends('layouts.AdminLTE.index')
@section('icon_page', 'ticket')
@section('title', 'Voucher')
@section('menu_pagina')
@section('content')
    <div class="row">
        @foreach ($list as $item)
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-{{ $item['bg-color'] }} d-flex">
                    <span class="info-box-icon d-flex"><i class="fa fa-bookmark-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Profile : <b>{{ $item['name'] }}</b></span>
                        <span class="info-box-number">{{ $item['total'] }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 65%"></div>
                        </div>
                        <span class="progress-description">
                            <div class="btn-group">
                                <form action="{{ route('hotspot.user.list', ['profile' => $item['name']]) }}" method="get">
                                    <button type="submit" class="act-view-more btn btn-{{ $item['btn-color'] }}"
                                        style="border: none !important">
                                        <i class="fa fa-list"></i> View More
                                    </button>
                                </form>
                                <button type="button" class="act-generate btn btn-{{ $item['btn-color'] }}"
                                    style="border: none !important" value="{{ $item['name'] }}" data-toggle="modal"
                                    data-target="#modal-generate">
                                    <i class="fa fa-plus"></i> Generate
                                </button>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- form generate user --}}
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
                                    <input type="number" class="form-control" id="fGenerateQty" name="qty"
                                        placeholder="Default: 1">
                                </div>
                            </div>
                            {{-- server --}}
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Server</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="server" id="fGenerateServer">
                                        @foreach ($formdata['server'] as $item)
                                            <option>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- name length --}}
                            <div class="form-group">
                                <label for="fGenerateLength" class="col-sm-3 control-label">Name Length</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="fGenerateLength" name="length"
                                        placeholder="Default: 4">
                                </div>
                            </div>
                            {{-- Prefix --}}
                            <div class="form-group">
                                <label for="fGeneratePrefix" class="col-sm-3 control-label">Prefix</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="fGeneratePrefix" name="prefix"
                                        placeholder="Default: user">
                                </div>
                            </div>
                            {{-- profile --}}
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Profile</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="profile" id="fGenerateProfile">
                                        @foreach ($list as $item)
                                            <option>{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- time limit --}}
                            <div class="form-group">
                                <label for="fGenerateTimeLimit" class="col-sm-3 control-label">Time Limit</label>
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
                                <label for="fGenerateDataLimit" class="col-sm-3 control-label">Data Limit</label>

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

    <script type="text/javascript">
        $(document).ready(function() {
            $(".act-generate").on("click", function() {
                var optProfile = $(this).val();
                $("#fGenerateProfile").empty()
                    .append(
                        $("<option></option>")
                        .attr("value", optProfile)
                        .text(optProfile)
                    ).attr('disabled', 'disabled');
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
