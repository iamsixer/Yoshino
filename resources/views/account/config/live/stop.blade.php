@extends('account.account')

@section('config')
    <div class="card">
        <div class="card-header">
            <h4>推流信息
                <span class="live-sub-title" id="setting-notice">
                    <a href="#"><i class="fa fa-info"></i> 超过12小时以上请重新创建直播</a>
                </span>
            </h4>
        </div>
        <div class="card-block">
            <div class="input-group input-group-padding-bottom">
                <span class="input-group-addon">推流地址：</span>
                <input type="text" class="form-control" value="rtmp://w.gslb.lecloud.com/live" readonly>
            </div>
            <div class="input-group input-group-padding-bottom">
                <span class="input-group-addon">推流码：</span>
                <input type="text" class="form-control" id="push_url" value="****************" readonly>
                <span class="input-group-btn">
                    <button class="btn btn-info-outline" id="show_push_url" type="button">显示</button>
                </span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>结束直播</h4>
        </div>
        <div class="card-block">
            <form method="post" action="{{ url(route('live_stop')) }}">
                {{ csrf_field() }}
                <h5 class="user-center-left">您有一个正在进行的直播</h5>
                <button type="submit" class="btn btn-danger-outline">结束直播</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        show_push_url.onclick = function () {
            $.ajax({
                type: 'GET',
                url: '{{ url('/api/user/live/getpushurl') }}',
                dataType: 'json',
                cache: false,
                success: function (data) {
                    if(data.pushUrl){
                        push_url.value = data.pushUrl;
                        show_push_url.innerHTML = '刷新';
                    }
                }
            });
        }
    </script>
@endsection