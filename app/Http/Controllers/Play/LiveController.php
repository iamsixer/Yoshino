<?php

namespace App\Http\Controllers\Play;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Category;
use App\Models\User;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Liveinfo;
use App\Facades\Leancloud;
use App\Models\Userinfo;

class LiveController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //首页顶部直播获取
        $random_live = Liveinfo::select('activityId')->where('ctime', '>', time() - 43200)->orderByRaw("RAND()")->first();

        if ($random_live) {
            $activityId = $random_live['activityId'];
        } else {
            $activityId = 'A2016060100001e4';
        }

        //获取正在进行的直播数量
        $living_count = Liveinfo::where('ctime', '>', time() - 43200)->count();
        //获取最后创建的4个直播
        $live_info = [];
        $living_user = Liveinfo::select('uid')->where('ctime', '>', time() - 43200)->orderBy('id', 'desc')->limit(4)->get();
        foreach ($living_user as $user) {
            $arr['uid'] = $user['uid'];
            $user_info = Userinfo::select('cover', 'room_name', 'room_desc')->where('uid', $user['uid'])->first();
            $user_email = User::select('email')->where('id', $user['uid'])->first();
            $arr['title'] = $user_info['room_name'];
            $arr['email'] = $user_email['email'];
            $arr['cover'] = $user_info['cover'];
            $arr['description'] = $user_info['room_desc'];
            array_push($live_info, $arr);
        }

        //获取首页分类
        $categories = Category::select('uri', 'name', 'cover')->where('on_index', 1)->limit(6)->get();
        //获取分类数量
        $categories_count = Category::count();

        return view('video.index', [
            'activityId' => $activityId,
            'living_count' => $living_count,
            'live_info' => $live_info,
            'categories' => $categories,
            'categories_count' => $categories_count
        ]);
    }

    /**
     * 正在进行的全部直播
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function all()
    {
        $living_info = [];
        $living_user = Liveinfo::select('uid', 'title')->where('ctime', '>', time() - 43200)->orderBy('id', 'desc')->get();
        foreach ($living_user as $user) {
            $arr['uid'] = $user['uid'];
            $user_info = Userinfo::select('cover', 'room_name', 'room_desc')->where('uid', $user['uid'])->first();
            $user_email = User::select('email')->where('id', $user['uid'])->first();
            $arr['email'] = $user_email['email'];
            $arr['cover'] = $user_info['cover'];
            $arr['title'] = $user_info['room_name'] ? $user_info['room_name'] : 'Niconiconi';
            $arr['description'] = $user_info['room_desc'] ? $user_info['room_desc'] : '暂无简介';
            array_push($living_info, $arr);
        }

        $live_count = count($living_info);

        return view('video.all', [
            'title' => '全部直播',
            'count' => $live_count,
            'liveInfo' => $living_info,
        ]);
    }

    /**
     * 全部分类页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function directory()
    {
        //获取分类信息
        $categories = Category::select('uri', 'name', 'cover')->get();
        //获取分类数量
        $count = count($categories);

        return view('video.directory', [
            'title' => '全部分类',
            'count' => $count,
            'categories' => $categories
        ]);
    }

    /**
     * 分类页面
     * @param $uri
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category($uri)
    {
        //获取Category信息
        $category_info = Category::select('id', 'name')->where('uri', $uri)->first();

        if (!$category_info) {
            return response()->view('errors.404', [], 404);
        }

        $title = $category_info['name'];
        $category_id = $category_info['id'];

        //获取直播信息
        $living_info = [];
        $living_user = Liveinfo::select('uid', 'title')->where('ctime', '>', time() - 43200)->where('category_id', $category_id)->orderBy('id', 'desc')->get();
        foreach ($living_user as $user) {
            $arr['uid'] = $user['uid'];
            $user_info = Userinfo::select('cover', 'room_name', 'room_desc')->where('uid', $user['uid'])->first();
            $user_email = User::select('email')->where('id', $user['uid'])->first();
            $arr['email'] = $user_email['email'];
            $arr['cover'] = $user_info['cover'];
            $arr['title'] = $user_info['room_name'] ? $user_info['room_name'] : '暂未设置房间名';
            $arr['description'] = $user_info['room_desc'] ? $user_info['room_desc'] : '暂无简介';
            array_push($living_info, $arr);
        }

        $live_count = count($living_info);

        return view('video.category', [
            'title' => $title,
            'count' => $live_count,
            'liveInfo' => $living_info
        ]);

    }

    /**
     * 直播房间页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLive($id, Request $request)
    {
        $live_info = DB::table('liveinfo')->select('title', 'activityId', 'liveId')->where('uid', $id)->first();
        $user = User::select('name', 'email')->where('id', $id)->first();
        //判断有无该user
        if (!$user) {
            return redirect()->route('index');
        }
        //获取用户详细信息
        $user_info = Userinfo::select('roomId', 'room_name', 'room_desc', 'long_desc')->where('uid', $id)->first();

        if (!$user_info['roomId']) {
            $json = Leancloud::createRoom('room' . $id);
            $res_arr = json_decode($json, true);
            $roomId = array_key_exists('objectId', $res_arr) ? $res_arr['objectId'] : false;
            if ($roomId) {
                Userinfo::where('uid', $id)->update([
                    'roomId' => $roomId
                ]);
            }
        } else {
            $roomId = $user_info['roomId'];
        }

        $title = $user_info['room_name'];
        $description = Markdown::convertToHtml($user_info['long_desc']);

        if ($live_info) {
            $liveId = $live_info->liveId;
            $activityId = $live_info->activityId;
        } else {
            $liveId = null;
            $activityId = null;
        }

        $name = $user->name;
        $email = $user->email;
        $appId = Leancloud::getAppId();

        //判断是否flash模式播放
        if ($request->input('m') && ($request->input('m') == 'flash')) {
            return view('video.flash', [
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'name' => $name,
                'activityId' => $activityId,
                'email' => $email,
                'appId' => $appId,
                'roomId' => $roomId,
            ]);
        }

        return view('video.room', [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'name' => $name,
            'liveId' => $liveId,
            'email' => $email,
            'appId' => $appId,
            'roomId' => $roomId,
        ]);
    }

    public function getAbout()
    {
        return view('about', [
            'title' => '关于'
        ]);
    }
}
