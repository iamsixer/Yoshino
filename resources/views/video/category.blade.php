@extends('layouts.layout')

@section('banner')
    <div class="container-fluid top-bar">
        <div class="banner">
            <div class="main-content" style="margin-bottom: 2em">
                <div class="container-fluid">
                    <div class="category-header">
                        <h3>{{ $title or 'Niconiconi' }}
                            <span class="live-sub-title">
                            当前分类共有 <span style="color:#ff5371;">{{ $count }}</span> 个直播
                        </span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @if(!$count)
            <div class="container-fluid">
                <div class="sub-header">
                    <div style="width:100%;text-align: center">
                        <img style="width:100%;max-width: 400px;"
                             src="//s-img.niconico.in/large/a15b4afegw1f4ds5mrvwmj20fu0cin55.jpg">
                    </div>
                </div>
            </div>
        @else
            <div class="container-build row">
                @foreach($liveInfo as $value)
                    <div class="col-xs-6 col-sm-6 col-md-3">
                        <a href="{{ 'http://v.live.niconico.in/#!v/'.$value['uid'] }}">
                            <div class="card shadow-card no-margin-bottom">
                                <div class="live-card"
                                     style="background-image: url({{ $value['cover'] ? $value['cover'] : '//s-img.niconico.in/orj480/a15b4afegw1f3n1bg7jmvj23m32jou14.jpg' }});"></div>
                                <div class="live-card-title">
                                    <div class="live-card-avatar">
                                        <img src="//secure.gravatar.com/avatar/{{ md5($value['email']) }}?s=50">
                                    </div>
                                    <p class="card-text" style="color:white">{{ $value['title'] }}</p>
                                </div>
                            </div>
                        </a>
                        <p class="description-text">{{ $value['description'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $(".live-card").css("height", $(".live-card").width() * 0.63 + "px");
            $(window).resize(function () {
                $(".live-card").css("height", $(".live-card").width() * 0.63 + "px");
            });
        });
    </script>
@endsection