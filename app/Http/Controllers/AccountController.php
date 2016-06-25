<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Category;
use App\Models\Playinfo;
use App\Models\Userinfo;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

use App\Facades\Lecloud;
use App\Models\Liveinfo;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected $livestatus;

    //Account控制器,需验证通过
    public function __construct()
    {
        $this->middleware('auth');
        view()->share('title', '个人中心');
    }

    /**
     * 个人中心首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        //获取block状态
        if (Gate::denies('is-blocked')) {
            $blocked = true;
        } else {
            $blocked = false;
        }
        //获取直播状态
        $live_status = $this->getLiveStatus();

        return view('account.config.index', [
            'live_status' => $live_status,
            'blocked' => $blocked
        ]);
    }

    /**
     * 用户个人设置
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSetting()
    {
        return view('account.config.setting', [
            'title' => '资料设置'
        ]);
    }

    /**
     * 直播间信息设置
     */
    public function getManage()
    {
        //获取用户block状态
        if (Gate::denies('is-blocked')) {
            return redirect()->route('account');
        }
        $uid = $this->getUserId();
        //获取个人信息
        $room_info = Userinfo::select('cover', 'room_name', 'room_desc', 'long_desc', 'category_id')->where('uid', $uid)->first();
        //获取分类信息
        $categories = Category::select('id', 'name')->get();

        $cover_url = $room_info['cover'];
        $room_name = $room_info['room_name'];
        $room_desc = $room_info['room_desc'];
        $long_desc = $room_info['long_desc'];
        $category_id = $room_info['category_id'];

        return view('account.config.manage', [
            'title' => '直播间设置',
            'cover_url' => $cover_url,
            'room_name' => $room_name,
            'room_desc' => $room_desc,
            'long_desc' => $long_desc,
            'category_id' => $category_id,
            'categories' => $categories
        ]);
    }

    /**
     * 保存封面图
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCover(Request $request)
    {
        //获取用户block状态
        if (Gate::denies('is-blocked')) {
            return redirect()->route('account');
        }
        //获取UID
        $uid = Auth::user()->id;

        //判断request是否有文件
        if (!$request->hasFile('cover')) {
            return redirect()->route('account_manage');
        }
        $file = $request->file('cover');
        //判断文件上传过程中是否出错
        if (!$file->isValid()) {
            return redirect()->route('account_manage');
        }

        //获取文件后缀
        $fileType = substr($file->getClientOriginalName(), strrpos($file->getClientOriginalName(), '.'));
        //计算新文件名
        $filename = md5($file->getClientOriginalName() . time()) . $fileType;

        //上传临时文件到七牛
        $disk = \Storage::disk('qiniu');
        $disk->getDriver()->putFile($filename, $file->getRealPath());
        //获取文件地址
        $url = $disk->getDriver()->downloadUrl($filename);

        if ($url) {
            //保存地址
            $url = str_replace('http://', '//', $url);
            Userinfo::where('uid', $uid)->update(['cover' => $url]);
        }

        return redirect()->route('account_manage');
    }

    /**
     * 获取UID
     * @return mixed
     */
    protected function getUserId()
    {
        return Auth::user()->id;
    }


    /**
     * 获取直播状态
     * @return mixed
     */
    public function getLiveStatus()
    {
        $live_status = Liveinfo::where('uid', Auth::user()->id)->count();
        return $live_status;
    }

}
