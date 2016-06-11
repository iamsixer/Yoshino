@extends('layouts.auth')

@section('content')
    <div class="container-fluid">
        <div class="container login-register-content">
            <div class="sub-header">
                <h3>重置密码</h3>
            </div>
            <div class="card no-margin-bottom">
                <form method="POST" action="{{ url('/password/reset') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="card-block">
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
                        <button type="submit" class="btn btn-primary-outline">点击重置密码</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection