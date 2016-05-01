@extends('admin.app')

@section('config')
    <div class="mdl-card__title header-background-image">
        <h4 class="user-name" style="margin:5px;">视频详情页</h4>
    </div>
    <div class="mdl-card__supporting-text" style="text-align: center;width: auto;padding:0;overflow-x:auto;">
        @if($info)
            <h5 style="margin:60px 0;">{{ $info }}</h5>
        @elseif($videoUnique)
            <div id="playcard"></div>
        @endif
    </div>
    <div class="mdl-card__supporting-text" style="text-align: center;width: auto;">
        <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect" href="{{ url('admin/playinfo') }}">
            返回
        </a>
    </div>
@endsection

@section('js')
    @if($videoUnique)
        <script src="{{ url('js/bcloud.js') }}"></script>
        <script>
            var player = new CloudVodPlayer();
            player.init({uu:"{{ $uu }}",vu:"{{ $videoUnique }}"},'playcard');
            $(document).ready(function(){
                $("#playcard").css("height",$("#playcard").width()*0.5625+"px");
                $(window).resize(function(){
                    $("#playcard").css("height",$("#playcard").width()*0.5625+"px");
                });
            });
        </script>
    @endif
@endsection