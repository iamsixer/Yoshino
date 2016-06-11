@extends('layouts.auth')

@section('content')
    <div class="container-fluid">
        <div class="container login-register-content">
            <div class="sub-header">
                <h3>登录</h3>
            </div>
            <div class="card no-margin-bottom">
                <form method="POST" action="{{ url('/login') }}">
                    {!! csrf_field() !!}
                    <div class="card-block">
                        <fieldset class="form-group">
                            <label for="InputEmail">邮箱地址</label>
                            <input type="email" class="form-control" id="InputEmail" name="email"
                                   placeholder="Enter email" value="{{ old('email') }}" required>
                            <small class="text-muted">We'll never share your email with anyone else.</small>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="InputPassword">密码</label>
                            <input type="password" class="form-control" id="InputPassword" name="password"
                                   placeholder="Enter Password" required>
                        </fieldset>
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <span style="color: red">{{ $error }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary-outline">登录</button>
                        <a class="btn-link" href="{{ url('/password/reset') }}" style="margin-left: 1em;">忘记密码</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection