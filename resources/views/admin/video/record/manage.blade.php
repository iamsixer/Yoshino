@extends('admin.video')

@section('manage')
    <div class="card">
        <div class="card-header">
            <h4>编辑视频信息</h4>
        </div>
        <div class="card-block">
            <hr/>
            <form action="{{ url(route('admin_record_modify')) }}?vid={{ $play_info['id'] }}" method="post">
                {!! csrf_field() !!}
                <div class="form-group row">
                    <div class="col-sm-2">封面设置：</div>
                    <div class="col-sm-5">
                        <img src="{{ $play_info['cover'] }}" class="image-border" style="max-width: 100%;">
                    </div>
                    <div class="col-sm-5">
                        <h5 style="padding: .8em 0;"><i class="fa fa-play-circle"></i> 视频播放地址：</h5>
                        <a href="{{ url('video/ac'.$play_info['id']) }}" target="_blank"
                           style="word-wrap: break-word;word-break: normal;">
                            {{ url('video/ac'.$play_info['id']) }}
                        </a>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">视频名称：</div>
                    <div class="col-sm-5">
                        <input type="text" name="name" class="form-control" value="{{ $play_info['name'] }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">视频简介：</div>
                    <div class="col-sm-5">
                        <textarea class="form-control" rows="5" disabled></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary-outline">保 存</button>
                    </div>
                </div>
            </form>
            <hr/>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>上传者信息</h4>
        </div>
        <div class="card-block">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>UID</td>
                    <td>{{ $user_info['id'] }}</td>
                </tr>
                <tr>
                    <td>用户名</td>
                    <td>{{ $user_info['name'] }}</td>
                </tr>
                <tr>
                    <td>邮箱</td>
                    <td>{{ $user_info['email'] }}</td>
                </tr>
                <tr>
                    <td>上传时间</td>
                    <td>{{ $play_info['created_at'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>高级设置</h4>
        </div>
        <div class="card-block">
            <h5 style="padding:0 0 .8em 0">删除该视频
                <span class="live-sub-title" style="color: red">
                    <i class="fa fa-exclamation-triangle"></i> 该操作不可挽回，请谨慎操作
                </span>
            </h5>
            <button class="btn btn-danger">删除</button>
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