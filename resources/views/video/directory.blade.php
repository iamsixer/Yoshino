@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="sub-header">
            <h3>{{ $title or 'Niconiconi' }}
                <span class="live-sub-title">
                    共 <span style="color:#ff5371;">{{ $count }}</span> 个分类
                </span>
            </h3>
        </div>
        <div class="container-build row">
            @foreach($categories as $category)
                <div class="col-xs-4 col-sm-4 col-md-2">
                    <a href="{{ url('/category/'.$category['uri']) }}">
                        <div class="card shadow-card" style="margin-bottom: 2em">
                            <div class="directory-card"
                                 style="background-image:url('{{ $category['cover'] }}')"></div>
                            <div class="directory-card-title">
                                <p class="card-text" style="color:white">{{ $category['name'] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $(".directory-card").css("height", $(".directory-card").width() * 1.4 + "px");
            $(window).resize(function () {
                $(".directory-card").css("height", $(".directory-card").width() * 1.4 + "px");
            });
        });
    </script>
@endsection