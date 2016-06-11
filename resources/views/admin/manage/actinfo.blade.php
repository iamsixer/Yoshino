@extends('admin.admin')

@section('manage')
    <div class="card">
        <div class="card-header">
            <h4>直播详情</h4>
        </div>
        <div class="card-block">
            <table class="table table-striped" style="margin-bottom: 0">
                <tbody>
                <tr>
                    <td>直播名称</td>
                    <td>{{ $user_info['room_name'] }}</td>
                </tr>
                <tr>
                    <td>直播简介</td>
                    <td>{{ $user_info['room_desc'] }}</td>
                </tr>
                <tr>
                    <td>主播名</td>
                    <td>{{ $user['name'] }}</td>
                </tr>
                <tr>
                    <td>主播邮箱</td>
                    <td>{{ $user['email'] }}</td>
                </tr>
                <tr>
                    <td>直播地址</td>
                    <td>
                        <a href="{{ url('/u/'.$live_info['uid']) }}" target="_blank">
                            {{ url('/u/'.$live_info['uid']) }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>开始时间</td>
                    <td>{{ $live_info['created_at'] }}</td>
                </tr>
                <tr>
                    <td>活动ID</td>
                    <td>{{ $live_info['activityId'] }}</td>
                </tr>
                <tr>
                    <td>操作</td>
                    <td>
                        <form method="post" action="{{ url(route('admin_act_stop')) }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="uid" value="{{ $live_info['uid'] }}">
                            <button class="btn btn-danger-outline" type="submit">停止直播</button>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection