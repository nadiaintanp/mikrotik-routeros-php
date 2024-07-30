@extends('layouts.AdminLTE.index')
@section('icon_page', 'dashboard')
{{-- @section('title', 'Dashboard ') --}}
@section('menu_pagina')
@section('content')
    <div class="nav-tabs-custom" style="background: none !important; box-shadow: none !important;">
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header">
                <i class="fa fa-dashboard"></i> Dashboard
            </li>
            <li class="active">
                <a class="home-tabs-selection" href="{{ route('home.info') }}" data-target="#tabsBoardInformation"
                    data-toggle="tab">Board Information</a>
            </li>
            <li>
                <a class="home-tabs-selection" href="{{ route('home.voucher') }}" data-target="#tabsVoucher"
                    data-toggle="tab">Quick Voucher</a>
            </li>
        </ul>
        <div class="tab-content" style="background: none !important; padding: 40px 10px">
            {{-- bagian board information --}}
            <div class="tab-pane active" id="tabsBoardInformation"></div>
            {{-- bagian voucher --}}
            <div class="tab-pane" id="tabsVoucher"></div>
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
