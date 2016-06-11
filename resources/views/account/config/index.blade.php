@extends('account.account')

@section('config')
    <div class="card">
        <div class="card-header">
            <h4>个人信息</h4>
        </div>
        <div class="card-block">
            <div class="container-fluid top-bar row user-center-left">
                <div class="col-xs-12 col-md-3">
                    <img src="//secure.gravatar.com/avatar/{{ md5(Auth::user()->email) }}?s=130" width="100%"
                         class="image-border">
                </div>
                <div class="col-xs-12 col-md-9">
                    <h4 class="user-info-right">{{ Auth::user()->name }}
                        <span class="user-info-setting">
                            <a href="{{ url(route('account_setting')) }}"><i class="fa fa-pencil"></i> 修改昵称</a>
                        </span>
                    </h4>
                    <h6 class="user-info-right">{{ Auth::user()->email }}
                        <span class="user-info-setting">
                            <a href="{{ url(route('account_setting')) }}"><i class="fa fa-pencil"></i> 修改邮箱</a>
                        </span>
                    </h6>
                    <h6 class="user-info-right">
                        <span>直播状态：</span>
                        @if($live_status)
                            <span style="color:#0074d9">正在直播</span>
                            <span class="user-info-setting">
                                <a href="{{ url(route('live_manage')) }}"><i class="fa fa-play-circle"></i> 结束直播</a>
                            </span>
                        @else
                            <span style="color:#0074d9">未直播</span>
                            <span class="user-info-setting">
                                <a href="{{ url(route('live_manage')) }}"><i class="fa fa-play-circle"></i> 开始直播</a>
                            </span>
                        @endif
                    </h6>
                </div>
            </div>
            <hr/>
            <div class="container-fluid top-bar">
                <h6>直播间地址：
                    <span class="user-info-setting">
                        <a href="{{ 'http://v.live.niconico.in/#!v/'.Auth::user()->id }}" target="_blank">
                            <i class="fa fa-link"></i> {{ 'http://v.live.niconico.in/#!v/'.Auth::user()->id }}
                        </a>
                    </span>
                </h6>
            </div>
        </div>
    </div>
    @if($blocked)
        <div class="card">
            <div class="card-header">
                <h4><img src="//s-img.niconico.in/large/a15b4afegw1f4g1pb8wclg202201ljrc.jpg" style="padding-right: 1em">提示</h4>
            </div>
            <div class="card-block">
                <p>您的账户暂时不能创建直播</p>
                <p>请Telegram联系开通 <a href="https://telegram.me/VolioLiu"> @VolioLiu</a></p>
                <p>或者发送邮件至 <a href="mailto:volio.liu@gmail.com"> volio.liu@gmail.com</a> ，附上注册邮箱即可</p>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h4>厚颜无耻地求饮料钱</h4>
        </div>
        <div class="card-block">
            <div class="container-fluid top-bar row">
                <div class="col-xs-12 col-md-3">
                    <a href="http://ww2.sinaimg.cn/large/a15b4afegw1f4ewwerf4cj20nw0nwgmx.jpg" target="_blank">
                        <div class="alipay-card"></div>
                    </a>
                </div>
                <div class="col-xs-12 col-md-9">
                    <h6 class="user-info-right">点击有大图 =w=</h6>
                    <h6 class="user-info-right">如果你赞助了</h6>
                    <h6 class="user-info-right">我就给你一个么么哒</h6>
                </div>
            </div>
        </div>
    </div>
@endsection