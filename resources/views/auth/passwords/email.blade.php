@extends('layouts.auth')

@section('content')
    <div class="container-fluid">
        <div class="container login-register-content">
            <div class="sub-header">
                <h3>密码找回</h3>
            </div>
            <div class="card no-margin-bottom">
                <form method="POST" action="{{ url('/password/email') }}">
                    {!! csrf_field() !!}
                    <div class="card-block">
                        <fieldset class="form-group">
                            <label for="InputEmail">邮箱地址</label>
                            <input type="email" class="form-control" id="InputEmail" placeholder="Enter email" name="email" value="{{ old('email') }}" required>
                        </fieldset>
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <span style="color: red">{{ $error }}</span>
                            @endforeach
                        @endif
                        @if (session('status'))
                            <span style="color: red">{{ session('status') }}</span>
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary-outline">发送密码重置邮件</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection