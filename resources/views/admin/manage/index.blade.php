@extends('admin.admin')

@section('manage')
    <div class="card">
        <div class="card-header">
            <h4>用户信息</h4>
        </div>
        <div class="card-block">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>正常注册用户</td>
                    <td>{{ $user_num }}</td>
                    <td><a>查看</a></td>
                </tr>
                <tr>
                    <td>待审核的用户</td>
                    <td>{{ $banned_num }}</td>
                    <td><a href="{{ url(route('admin_users_blocked')) }}">查看</a></td>
                </tr>
                <tr>
                    <td>进行中的直播</td>
                    <td>{{ $act_num }}</td>
                    <td><a href="{{ url(route('admin_act_info')) }}">查看</a></td>
                </tr>
                <tr>
                    <td>待审核的视频</td>
                    <td>0</td>
                    <td><a href="#">查看</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>系统信息</h4>
        </div>
        <div class="card-block">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>当前域名</td>
                    <td>{{ url('') }}</td>
                </tr>
                <tr>
                    <td>服务器时间</td>
                    <td>{{ date("Y-m-d H:i:s") }}</td>
                </tr>
                <tr>
                    <td>系统版本</td>
                    <td>{{ php_uname() }}</td>
                </tr>
                <tr>
                    <td>PHP版本</td>
                    <td>{{ PHP_VERSION }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection