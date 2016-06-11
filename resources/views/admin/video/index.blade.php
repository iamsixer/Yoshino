@extends('admin.video')

@section('manage')
    <div class="card">
        <div class="card-header">
            <h4>视频管理总览</h4>
        </div>
        <div class="card-block">
            <table class="table table-striped" style="margin-bottom: 0">
                <tbody>
                <tr>
                    <td>直播已录制视频数量</td>
                    <td>{{ $record_num }}</td>
                    <td><a href="{{ url(route('admin_record_list')) }}">查看</a></td>
                </tr>
                <tr>
                    <td>待审核视频数量</td>
                    <td>0</td>
                    <td><a href="#">查看</a></td>
                </tr>
                <tr>
                    <td>已上传视频数量</td>
                    <td>0</td>
                    <td><a href="#">查看</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection