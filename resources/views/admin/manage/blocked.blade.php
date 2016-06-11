@extends('admin.admin')

@section('manage')
    <div class="card">
        <div class="card-header">
            <h4>待审核用户</h4>
        </div>
        <div class="card-block">
            <table class="table table-striped" style="margin-bottom: 0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>详情</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>
                            <a>查看详情</a>
                        </td>
                        <td>
                            <form method="post" action="{{ url(route('admin_unblock_user')) }}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="uid" value="{{ $user['id'] }}">
                                <button class="btn btn-primary-outline btn-sm" type="submit">通过审核</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $users->links() !!}
        </div>
    </div>
@endsection