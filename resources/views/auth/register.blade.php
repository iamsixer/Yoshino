@extends('layouts.auth')

@section('content')
    <div class="container-fluid">
        <div class="container login-register-content">
            <div class="sub-header">
                <h3>注册</h3>
            </div>
            <div class="card no-margin-bottom">
                <form method="POST" action="{{ url('/register') }}">
                    {!! csrf_field() !!}
                    <div class="card-block">
                        <fieldset class="form-group">
                            <label for="InputUserName">用户名</label>
                            <input type="text" class="form-control" id="InputUserName" placeholder="Enter Username" name="name" value="{{ old('name') }}" required>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="InputEmail">邮箱地址</label>
                            <input type="email" class="form-control" id="InputEmail" placeholder="Enter email" name="email" value="{{ old('email') }}" required>
                            <small class="text-muted">We'll never share your email with anyone else.</small>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="InputPassword">密码</label>
                            <input type="password" class="form-control" id="InputPassword" placeholder="Enter Password" name="password" required>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="ConfirmPassword">确认密码</label>
                            <input type="password" class="form-control" id="ConfirmPassword"
                                   placeholder="Confirm Password" name="password_confirmation" required>
                        </fieldset>
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <span style="color: red">{{ $error }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary-outline">注册</button>
                        <a class="btn-link" href="{{ url('/login') }}" style="margin-left: 1em;">已有账号？点此登录</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection