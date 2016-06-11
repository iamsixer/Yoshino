@extends('admin.admin')

@section('manage')
    <div class="card">
        <div class="card-header">
            <h4>正在进行的直播</h4>
        </div>
        <div class="card-block">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>UID</th>
                    <th>标题</th>
                    <th>直播ID</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($living_act as $value)
                    <tr>
                        <td>{{ $value['id'] }}</td>
                        <td>{{ $value['uid'] }}</td>
                        <td>{{ $value['title'] }}</td>
                        <td>{{ $value['activityId'] }}</td>
                        <td><a href="{{ url(route('admin_act_manage')).'?id='.$value['id'] }}">查看详细信息</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection