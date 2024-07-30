@extends('layouts.AdminLTE.index')
@section('icon_page', 'dashboard')
{{-- @section('title', 'Dashboard ') --}}
@section('menu_pagina')
@section('content')
    <h2 class="page-header">AdminLTE Custom Tabs</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a class="tab-selection" href="{{ route('home.test1') }}" data-target="#tab_1" id="tab1" data-toggle="tab">Tab 1</a></li>
                    <li><a class="tab-selection" href="{{ route('home.test2') }}" data-target="#tab_2" id="tab2" data-toggle="tab">Tab 2</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1"></div>
                    <div class="tab-pane" id="tab_2"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".tab-selection").on("click", function(){
                console.log("Tsdasd");
                var $this = $(this),
                    loadurl = $this.attr('href'),
                    targ = $this.attr('data-target');

                $.get(loadurl, function(data) {
                    $(targ).html(data);
                });

                $this.tab('show');
                return false;
            });
        });
    </script>
@endsection
