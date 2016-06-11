@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="sub-header">
            <h3>{{ $title or 'Niconiconi' }}</h3>
        </div>
        <div class="container-build row">
            <div class="col-xs-12 col-md-3 user-center-left">
                <div class="list-group">
                    <a href="{{ url(route('record_all')) }}" class="list-group-item
                    @if(URL::current() == url(route('record_all'))) active @endif">
                        <i class="fa fa-video-camera"></i> 录制视频管理
                    </a>
                    <a href="#" class="list-group-item">
                        <i class="fa fa-folder"></i> 上传视频管理
                    </a>
                    <a href="{{ url(route('account')) }}" class="list-group-item">
                        <i class="fa fa-user"></i> 返回个人中心
                    </a>
                </div>
            </div>
            <div class="col-xs-12 col-md-9">
                @yield('config')
            </div>
        </div>
    </div>
@endsection