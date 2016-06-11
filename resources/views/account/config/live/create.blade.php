@extends('account.account')

@section('config')
    <div class="card">
        <div class="card-header">
            <h4>创建直播</h4>
        </div>
        <div class="card-block">
            <form method="post" action="{{ url(route('live_create')) }}">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label for="input_name" class="col-sm-2 form-control-label">直播名称：</label>
                    <div class="col-sm-10">
                        <input type="text" name="live_name" class="form-control" id="input_name" value="{{ $room_name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="input_desc" class="col-sm-2 form-control-label">直播简介：</label>
                    <div class="col-sm-10">
                        <input type="text" name="live_des" class="form-control" id="input_desc" value="{{ $room_desc }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="category_id" class="col-sm-2 form-control-label">直播分类：</label>
                    <div class="col-sm-10">
                            <select class="form-control" id="category_id" name="category_id">
                                @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}"
                                            @if($category['id'] == $category_id)
                                            selected
                                            @endif
                                    >{{ $category['name'] }}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2">清晰度：</label>
                    <div class="col-sm-10">
                        <div class="checkbox checkbox-inline no-padding-left">
                            <label class="c-input c-checkbox">
                                <input type="checkbox" name="rate1">
                                <span class="c-indicator"></span> 标清
                            </label>
                        </div>
                        <div class="checkbox checkbox-inline no-padding-left">
                            <label class="c-input c-checkbox">
                                <input type="checkbox" name="rate2">
                                <span class="c-indicator"></span> 高清
                            </label>
                        </div>
                        <div class="checkbox checkbox-inline no-padding-left">
                            <label class="c-input c-checkbox">
                                <input type="checkbox" name="rate3">
                                <span class="c-indicator"></span> 超清
                            </label>
                        </div>
                        <div class="checkbox checkbox-inline no-padding-left">
                            <label class="c-input c-checkbox">
                                <input type="checkbox" name="rate4">
                                <span class="c-indicator"></span> 1080P
                            </label>
                        </div>
                        <div class="checkbox checkbox-inline no-padding-left">
                            <label class="c-input c-checkbox">
                                <input type="checkbox" name="rate5" checked>
                                <span class="c-indicator"></span> 原画
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2">直播录制</label>
                    <div class="col-sm-10">
                        <div class="checkbox checkbox-inline no-padding-left">
                            <label class="c-input c-checkbox">
                                <input type="checkbox" name="record">
                                <span class="c-indicator"></span> 开启直播录制
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-secondary">开始直播</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection