@extends('layouts.AdminLTE.index')
@section('icon_page', 'file-text-o')
@section('title', 'Logs ')
@section('dont_show_navbar')
@section('menu_pagina')
@section('content')
    <div class="nav-tabs-custom" style="background: none !important; box-shadow: none !important;">
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header">
                <i class="fa fa-file-text-o"></i> Logs
            </li>
            <li class="active">
                <a class="home-tabs-selection" href="{{ route('logs.view', ['type'=>'hotspot']) }}" data-target="#tabsLogHotspot"
                    data-toggle="tab">Hotspot</a>
            </li>
            {{-- <li>
                <a class="home-tabs-selection" href="{{ route('logs.view', ['type'=>'user']) }}" data-target="#tabsLogUser"
                    data-toggle="tab">User</a>
            </li> --}}
        </ul>
        <div class="tab-content" style="background: none !important; padding: 40px 10px">
            <div class="tab-pane active" id="tabsLogHotspot"></div>
            {{-- <div class="tab-pane" id="tabsLogUser"></div> --}}
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {

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

            tabsLoaderHome($("li.active .home-tabs-selection"));

            $(".home-tabs-selection").on("click", function(e) {
                tabsLoaderHome($(this))
            });


        });
    </script>
@endsection
