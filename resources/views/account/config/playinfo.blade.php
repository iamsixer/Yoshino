@extends('account.app')

@section('config')
    <div class="mdl-card__title header-background-image">
        <h4 class="user-name" style="margin:5px;">所有录制视频</h4>
    </div>
    <div class="mdl-card__supporting-text" style="text-align: center;width: auto;padding:0;overflow-x:auto;">
        @if($playInfo)
            <table class="mdl-data-table mdl-js-data-table user-config">
                <thead>
                <tr>
                    <th class="mdl-data-table__cell--non-numeric">ID</th>
                    <th class="mdl-data-table__cell--non-numeric">ActivityID</th>
                    <th class="mdl-data-table__cell--non-numeric">录制时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($playInfo as $value)
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric">{{ $value['id'] }}</td>
                        <td class="mdl-data-table__cell--non-numeric">{{ $value['activityId'] }}</td>
                        <td class="mdl-data-table__cell--non-numeric">{{ $value['ctime'] }}</td>
                        <td>
                            <a href="{{ url('/account/playinfo?id='.$value['id']) }}">查看</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <h5 style="margin:60px 0;">没有已录制的视频</h5>
        @endif
    </div>
    <div class="mdl-card__supporting-text" style="text-align: center;width: auto;">
        @if($playInfo&&($previousPageUrl||$nextPageUrl))
            @if($previousPageUrl)
                <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect" href="{{ $previousPageUrl }}">
                    上一页
                </a>
            @endif
            @if($nextPageUrl)
                <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect" href="{{ $nextPageUrl }}">
                    下一页
                </a>
            @endif
        @else
            <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect" href="{{ url('/account') }}">
                返回
            </a>
        @endif
    </div>
@endsection