@extends('layouts.video')

@section('content')
    <div class="container-fluid">
        <div class="sub-header">
            <h3 class="room-title">
                {{ $video_info['name'] }}
                <span class="live-sub-title">
                    播放次数 : <span style="color:#ff5371;">{{ $video_info['views'] }}</span>
                </span>
            </h3>
        </div>
        <div class="container-build row">
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="card shadow-card live-card" id="video_card"></div>
                <div class="card shadow-card" id="video_card">
                    <div style="display: flex;align-items: center;">
                        <img src="//cdn.v2ex.com/gravatar/{{ md5($author_info['email']) }}?s=60"
                             class="live-title-avatar" style="margin: .4em">
                        <span style="padding: 0 .4em">用户: <a href="#">{{ $author_info['name'] }}</a></span>
                        <span style="padding: 0 .4em">发布于: {{ $video_info['created_at'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4">
                <div id="disqus_thread"></div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/js/vendor/bcloud.js') }}"></script>
    <script>
        var player = new CloudVodPlayer();
        player.init({uu: "{{ $uu }}", vu: "{{ $video_info['videoUnique'] }}"}, 'video_card');
        $(document).ready(function () {
            var playerHeight = $(".live-card").width() * 0.5625;
            $(".live-card").css("height", playerHeight + "px");
            $(window).resize(function () {
                $(".live-card").css("height", $(".live-card").width() * 0.5625 + "px");
            });
        });
    </script>
    <script>
        var disqus_config = function () {
            this.page.url = "{{ URL::current() }}";
            this.page.identifier = "{{ $video_info['videoId'] }}";
        };
        (function () {
            var d = document, s = d.createElement('script');
            s.src = '//nicoin.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
@endsection