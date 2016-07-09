@extends('account.account')

@section('config')
    <div class="card">
        <div class="card-header">
            <h4>资料设置
                <span class="live-sub-title" id="setting-notice" style="display: none">
                    <a href="#"><i class="fa fa-info"></i> 保存成功</a>
                </span>
            </h4>
        </div>
        <div class="card-block">
            <div class="input-group input-group-padding-bottom">
                <span class="input-group-addon">昵称：</span>
                <input type="text" id="setting_user_name" class="form-control" value="{{ Auth::user()->name }}">
                <span class="input-group-btn">
                    <button class="btn btn-info-outline" id="setting_user_name_btn" type="button">保存</button>
                </span>
            </div>
            <div class="input-group">
                <span class="input-group-addon">邮箱：</span>
                <input type="email" id="setting_user_email" class="form-control" value="{{ Auth::user()->email }}">
                <span class="input-group-btn">
                    <button class="btn btn-info-outline" id="setting_user_email_btn" type="button">保存</button>
                </span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>头像设置</h4>
        </div>
        <div class="card-block">
            <div class="container-fluid top-bar row user-center-left">
                <div class="col-xs-12 col-md-3">
                    <img src="//cdn.v2ex.com/gravatar/{{ md5(Auth::user()->email) }}?s=130" width="100%"
                         class="image-border">
                </div>
                <div class="col-xs-12 col-md-9">
                    <h4 class="user-info-right">Gravatar 头像
                        <span class="user-info-setting">
                            <a href="https://cn.gravatar.com" target="_blank"><i class="fa fa-pencil"></i> 修改头像</a>
                        </span>
                    </h4>
                    <h6 class="user-info-right user-info-right-content">
                        本站头像使用 Gravatar 头像，如要修改请移步至 Gravatar.com 修改，修改完成后本站会自动完成同步。
                    </h6>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/js/ajaxform.js') }}"></script>
    <script>
        function getToken() {
            return '{{ csrf_token() }}';
        }
    </script>
@endsection