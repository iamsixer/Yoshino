@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="sub-header">
            <h3>{{ $title or 'Niconiconi' }}</h3>
        </div>
        <div class="container-build row">
            <div class="col-xs-12 col-md-3 user-center-left">
                <div class="list-group">
                    <a href="{{ url(route('admin_video_index')) }}" class="list-group-item
                    @if(URL::current() == url(route('admin_video_index'))) active @endif">
                        <i class="fa fa-bars"></i> 视频管理总览
                    </a>
                    <a href="{{ url(route('admin_record_list')) }}" class="list-group-item
                    @if(URL::current() == url(route('admin_record_list'))) active @endif">
                        <i class="fa fa-video-camera"></i> 录制视频管理
                    </a>
                    <a href="#" class="list-group-item">
                        <i class="fa fa-cloud-upload"></i> 上传视频管理
                    </a>
                    <a href="#" class="list-group-item">
                        <i class="fa fa-folder"></i> 待审核视频
                    </a>
                    <a href="{{ url(route('admin_index')) }}" class="list-group-item">
                        <i class="fa fa-user"></i> 返回管理后台
                    </a>
                </div>
            </div>
            <div class="col-xs-12 col-md-9">
                @yield('manage')
            </div>
        </div>
    </div>
@endsection