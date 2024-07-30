@extends('layouts.AdminLTE.index')
@section('icon_page', 'send-o')
@section('title', 'Coming Soon ')
@section('dont_show_navbar')
@section('menu_pagina')
@section('content')
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <b>WE</b> ARE <b>SORRY</b> !!!
        </div>

        <!-- /.lockscreen-item -->
        <div class="help-block text-center">
            {!! \App\Models\Config::find(1)->app_name !!}
        </div>
        <div class="text-center">
            This page will be available soon. Stay tuned !!!
        </div>
    </div>
@endsection
