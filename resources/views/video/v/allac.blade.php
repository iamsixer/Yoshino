@extends('layouts.video')

@section('content')
    <div class="container-fluid">
        <div class="sub-header">
            <h3>{{ $title }}</h3>
        </div>
        <div class="container-build row">
            @foreach($record_videos as $video)
                <div class="col-sm-3">
                    <a href="{{ url('/video/ac'.$video['id']) }}">
                        <div class="card shadow-card no-margin-bottom">
                            <div class="video-card"
                                 style="background-image: url('{{ $video['cover'] ? $video['cover'] : "//s-img.niconico.in/orj480/a15b4afegw1f174um1elhj20g4093abh.jpg" }}');"></div>
                            <div class="live-card-title">
                                <div class="live-card-avatar">
                                    <img src="//cdn.v2ex.com/gravatar/{{ md5($users[$video['uid']]['email']) }}?s=50">
                                </div>
                                <p class="card-text" style="color:white">{{ $video['name'] }}</p>
                            </div>
                        </div>
                    </a>
                    <div class="video-card-description">
                        <li>用户：<a href="#">{{$users[$video['uid']]['name']}}</a></li>
                        <li>播放：<span style="color: #688ba2;">{{$video['views'] }}</span></li>
                        <li>发布于：{{$video['created_at'] }}</li>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="container-fluid">
            {!! $record_videos->links() !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $(".video-card").css("height", $(".video-card").width() * 0.63 + "px");
            $(window).resize(function () {
                $(".video-card").css("height", $(".video-card").width() * 0.63 + "px");
            });
        });
    </script>
@endsection