@extends('account.account')

@section('config')
    <div class="card">
        <div class="card-header">
            <h4>直播间设置
                <span class="live-sub-title" id="setting-notice" style="display: none">
                    <a href="#"><i class="fa fa-info"></i> 保存成功</a>
                </span>
            </h4>
        </div>
        <div class="card-block">
            <div class="input-group input-group-padding-bottom">
                <span class="input-group-addon">房间名：</span>
                <input type="text" id="setting_room_name" class="form-control" value="{{ $room_name }}">
                <span class="input-group-btn">
                    <button class="btn btn-info-outline" id="setting_room_name_btn" type="button">保存</button>
                </span>
            </div>
            <div class="input-group input-group-padding-bottom">
                <span class="input-group-addon">短简介：</span>
                <input type="text" id="setting_room_desc" class="form-control" value="{{ $room_desc }}">
                <span class="input-group-btn">
                    <button class="btn btn-info-outline" id="setting_room_desc_btn" type="button">保存</button>
                </span>
            </div>
            <fieldset class="form-group">
                <label for="exampleSelect1">直播分类：</label>
                <select class="form-control" id="category_id">
                    @foreach($categories as $category)
                        <option value="{{ $category['id'] }}"
                                @if($category['id'] == $category_id)
                                selected
                                @endif
                        >{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </fieldset>
            <fieldset class="form-group">
                <label for="long_desc_area">长简介（支持 markdown）：</label>
                <textarea class="form-control" id="long_desc_area" rows="5">{{ $long_desc }}</textarea>
            </fieldset>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>封面设置
                <span class="live-sub-title">
                    <a href="#"><i class="fa fa-info"></i> 最大1M</a>
                </span>
            </h4>
        </div>
        <div class="card-block">
            @if($cover_url)
                <img src="{{ $cover_url }}" class="image-border"
                     style="width:100%">
            @else
                <h5 style="text-align: center;padding: .6em 0;">您尚未设置封面</h5>
            @endif
            <form method="post" action="{{ url(route('account_cover')) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="input-group input-group-padding-top" style="margin:0 auto;">
                    <label class="file" style="margin-right: 8px;">
                        <input type="file" id="file" name="cover" accept="image/*">
                        <span class="file-custom"></span>
                    </label>
                    <button type="submit" class="btn btn-primary-outline" style="margin-top:8px;">完成上传</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/js/0.1/ajaxform.js') }}"></script>
    <script>
        function getToken() {
            return '{{ csrf_token() }}';
        }
        file.onchange = function () {
            $(".file-custom").addClass("file-input");
        }
    </script>
@endsection