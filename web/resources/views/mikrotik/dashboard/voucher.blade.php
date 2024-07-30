<div class="row">
    <div class="col-md-3 col-sm-4 col-xs-12">
        <div class="box box-warning p-3">
            <div class="box-header with-border">
                <h3 class="box-title">Quick Add Profile</h3>
            </div>
            <div class="box-body">
                <form id="form-profile-add" class="form-group">
                    <div class="form-group">
                        <label for="form-profile-name">Profile Name</label>
                        <input type="text" class="form-control" name="name" id="form-profile-name"
                            placeholder="Enter profile name">
                    </div>
                    <div class="form-group">
                        <label for="form-profile-address-pool">Address Pool</label>
                        <select name="address-pool" id="form-profile-address-pool" class="form-control">
                            @foreach ($listPool as $item)
                                <option>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="form-profile-shared-users">Shared Users</label>
                        <input type="text" class="form-control" name="shared-users" id="form-profile-shared-users"
                            placeholder="Enter shared-users">
                    </div>
                    <div class="form-group">
                        <label for="form-profile-rate-limit">Rate Limit [up/down]</label>
                        <input type="text" class="form-control" name="rate-limit" id="form-profile-rate-limit"
                            placeholder="Enter rate-limit">
                        <small class="help-block">Example <b>512k/1M</b></small>
                    </div>
                    {{-- <div class="form-group">
                        <label for="form-profile-expired-mode">Expired Mode</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="expired-mode" value="0" checked>
                                None
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="expired-mode" value="rem">
                                Remove
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="expired-mode" value="ntf">
                                Notice
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="expired-mode" value="remc">
                                Remove & Record
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="expired-mode" value="ntfc">
                                Notice & Record
                            </label>
                        </div>
                    </div> --}}
                    {{-- <div class="form-group">
                        <label for="form-profile-lock-user">Lock User</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="lock-user" id="form-profile-lock-1" value="true">
                                Enabled
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="lock-user" id="form-profile-lock-2" value="false" checked>
                                Disabled
                            </label>
                        </div>
                    </div> --}}
                </form>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-default" id="act-profile-reset">Reset</button>
                <button type="submit" class="btn btn-info pull-right" id="act-profile-save">Save</button>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="row">
            @foreach ($listHotspot as $item)
                <div class="col-md-2 col-sm-5 col-xs-12">
                    <div class="info-box {{ $item['bg-color'] ?? 'bg-aqua' }} d-flex">
                        <span class="info-box-icon d-flex"><i class="fa fa-bookmark-o"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Profile : <b>{{ $item['name'] ?? '-' }}</b></span>
                            <span class="info-box-number">{{ $item['total'] ?? 0 }}</span>

                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                <div class="btn-group">
                                    <form action="{{ route('hotspot.user.list', ['profile' => $item['name'] ?? '-']) }}"
                                        method="get">
                                        <button type="submit" class="act-view-more btn {{ $item['btn-color'] }}"
                                            style="border: none !important">
                                            <i class="fa fa-list"></i> View User
                                        </button>
                                    </form>
                                    <button type="button"
                                        class="act-profile-detail btn {{ $item['btn-color'] ?? 'btn-info' }}"
                                        style="border: none !important" value="{{ $item['id'] ?? '' }}"
                                        data-toggle="modal" data-target="#modal-detail">
                                        <i class="fa fa-cubes"></i> Detail
                                    </button>
                                    <button type="button"
                                        class="act-generate btn {{ $item['btn-color'] ?? 'btn-info' }}"
                                        style="border: none !important" value="{{ $item['name'] ?? '-' }}"
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
    </div>
</div>

<form class="form-horizontal" id="form-profile-detail">
    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail Profile</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="form-profile-name">Profile Name</label>
                            <input type="hidden" name=".id" id="form-detail-profile-id">
                            <input type="text" class="form-control" name="name" id="form-detail-profile-name"
                                placeholder="Enter profile name">
                        </div>
                        <div class="form-group">
                            <label for="form-profile-address-pool">Address Pool</label>
                            <select name="address-pool" id="form-detail-profile-address-pool" class="form-control">
                                @foreach ($listPool as $item)
                                    <option>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="form-profile-shared-users">Shared Users</label>
                            <input type="text" class="form-control" name="shared-users" id="form-detail-profile-shared-users"
                                placeholder="Enter shared-users">
                        </div>
                        <div class="form-group">
                            <label for="form-profile-rate-limit">Rate Limit [up/down]</label>
                            <input type="text" class="form-control" name="rate-limit" id="form-detail-profile-rate-limit"
                                placeholder="Enter rate-limit">
                            <small class="help-block">Example <b>512k/1M</b></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal" id="btn-profile-reset">Close</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="btn-profile-delete">Delete</button>
                    <button type="button" class="btn btn-primary" id="btn-profile-updated">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

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
        var request;
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
                },
                success: function(resp) {

                    $("#modal-generate").modal("toggle");

                    Swal.fire({
                        text: resp.message,
                        icon: resp.status
                    });
                },
                error: function(_) {
                    var resp = _.responseJSON;
                    Swal.fire({
                        text: resp.message,
                        icon: 'error'
                    })
                }
            });
        });

        function resetForm(params) {
            $("#form-profile-name").val("");
            $("#form-profile-address-pool").val("none");
            $("#form-profile-shared-users").val("");
            $("#form-profile-rate-limit").val("");
            $("input[name='lock-user'][value='false']").prop("checked", true);
        }
        $("#act-profile-reset").on("click", function(e) {
            resetForm();
        });

        function tabsLoaderHome(actTab) {
            console.log("load content...");

            var $this = actTab;
            var loadurl = $this.attr("href");
            var targ = $this.attr("data-target");

            $.get(loadurl, function(data) {
                $(targ).html(data);
            });

            $this.tab('show');
            return false;
        }

        function doProfile(action) {
            var url = "{{ route('hotspot.user.profile.add') }}";

            var formid = action == "update" ? "#form-profile-detail" : "#form-profile-add";

            var data = $(formid).serializeArray();

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "data": data,
                    "action": action
                },
                success: function(_) {
                    if (action == "update") {
                        $("#modal-detail").modal("toggle");
                    }

                    Swal.fire(
                        _.title,
                        _.message,
                        _.status
                    );

                    tabsLoaderHome($("li.active .home-tabs-selection"));
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

        $("#act-profile-save").on("click", function(e) {
            doProfile("add");
        });

        $("#btn-profile-updated").on("click", function(e) {
            doProfile("update");
        });

        $(".act-profile-detail").on("click", function(e) {
            e.preventDefault();

            var url = "{{ route('hotspot.user.profile.data') }}";

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "data": $(this).val()
                },
                success: function(_) {
                    // console.log(_);
                    $("#form-detail-profile-id").val(_[".id"]);
                    $("#form-detail-profile-name").val(_["name"]);
                    $("#form-detail-profile-address-pool").val(_["address-pool"] || "none");
                    $("#form-detail-profile-shared-users").val(_["shared-users"]);
                    $("#form-detail-profile-rate-limit").val(_["rate-limit"]);
                }
            });
        });

        $("#btn-profile-delete").on("click", function(e) {
            var data_id = $("#form-detail-profile-id").val();
            Swal.fire({
                    title: `Are you sure ?`,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url_del = "{{ route('hotspot.user.profile.delete') }}";
                        $.ajax({
                            url: url_del,
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
                                tabsLoaderHome($("li.active .home-tabs-selection"));
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
    });
</script>
