<?php

namespace App\Http\Controllers;

use App\Models\Playinfo;
use App\Facades\Lecloud;
use App\Models\Userinfo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Liveinfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        view()->share('title', '管理后台');
    }

    /**
     * 管理员首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $user_count = User::where('status', 1)->count();
        $banned_count = User::where('status', 0)->count();
        $act_count = Liveinfo::count();

        return view('admin.manage.index', [
            'user_num' => $user_count,
            'banned_num' => $banned_count,
            'act_num' => $act_count,
        ]);
    }

    /**
     * 获取正在直播的活动
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getActivity()
    {
        $living_act = Liveinfo::select('id', 'uid', 'title', 'activityId')->get();

        return view('admin.manage.activity', [
            'title' => '正在进行的直播',
            'living_act' => $living_act,
        ]);
    }

    /**
     * 获取未审核用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBlockedUsers()
    {
        $users = User::select('id', 'name', 'email')->where('status', '0')->orderBy('id', 'desc')->paginate(30);

        return view('admin.manage.blocked', [
            'title' => '待审核用户',
            'users' => $users
        ]);
    }

    /**
     * 获取正常注册用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNormalUsers()
    {
        $users = User::select('id', 'name', 'email')->where('status', '1')->orderBy('id', 'desc')->paginate(30);

        return view('admin.manage.users', [
            'title' => '已注册用户',
            'users' => $users
        ]);
    }

    /**
     * 视频管理页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getVideo()
    {
        $record_num = Playinfo::count();
        return view('admin.video.index', [
            'title' => '视频管理',
            'record_num' => $record_num
        ]);
    }

    /**
     * 获取全部录制视频
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRecordVideos()
    {
        $record_videos = Playinfo::select('id', 'name', 'cover', 'created_at')->orderBy('id', 'desc')->paginate(12);

        return view('admin.video.record.list', [
            'title' => '录制视频管理',
            'record_videos' => $record_videos
        ]);
    }

    /**
     * 单视频管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getRecordVideoManage(Request $request)
    {
        //数据验证
        $this->validate($request, [
            'vid' => 'required|integer'
        ]);

        $record_video_id = $request->get('vid');
        $play_info = Playinfo::select('id', 'uid', 'name', 'activityId', 'ctime', 'videoId', 'videoUnique', 'cover', 'created_at')->where('id', $record_video_id)->first();
        //如果无数据自动跳转
        if (!$play_info) {
            return redirect()->route('admin_record_list');
        }
        //无videoId自动更新
        if (!$play_info['videoId']) {
            $play_info = $this->modifyRecordVideoInfo($play_info, $record_video_id);
        }
        //有videoId无封面自动更新
        if ($play_info['videoId'] && (!$play_info['cover'])) {
            $play_info = $this->modifyRecordVideoCover($play_info, $record_video_id);
        }
        //获取上传者信息
        $user_info = User::select('id', 'name', 'email')->where('id', $play_info['uid'])->first();

        return view('admin.video.record.manage', [
            'title' => '编辑视频信息',
            'play_info' => $play_info,
            'user_info' => $user_info
        ]);
    }

    /**
     * @param Request $request
     * 更新视频信息
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRecordVideoModify(Request $request)
    {
        $this->validate($request, [
            'vid' => 'required|integer',
            'name' => 'required|max:255'
        ]);

        $record_video_id = $request->input('vid');
        $play_info = Playinfo::select('id', 'uid')->where('id', $record_video_id)->first();
        //如果无数据自动跳转
        if (!$play_info) {
            return redirect()->route('admin_record_list');
        }

        Playinfo::where('id', $record_video_id)->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin_record_manage');
    }

    /**
     * 更新录制视频信息
     * @param $play_info
     * @param $record_video_id
     * @return mixed
     */
    private function modifyRecordVideoInfo($play_info, $record_video_id)
    {
        $json = Lecloud::getPlayInfo($play_info['activityId']);

        try {
            $arr = json_decode($json, true);
            //更新视频信息
            if ($arr['machineInfo']) {
                Playinfo::where('id', $record_video_id)->update([
                    'videoId' => $arr['machineInfo'][0]['videoId'],
                    'videoUnique' => $arr['machineInfo'][0]['videoUnique']
                ]);

                $play_info['videoId'] = $arr['machineInfo'][0]['videoId'];
                $play_info['videoUnique'] = $arr['machineInfo'][0]['videoUnique'];
            } else {
                //录制视频超过12小时无数据删除
                if ((time() - $play_info['ctime']) > 86400) {
                    Playinfo::where('id', $record_video_id)->delete();
                    return redirect()->route('admin_record_list');
                }
            }
        } catch (\Exception $e) {

        }

        return $play_info;
    }

    /**
     * 更新录制视频封面
     * @param $play_info
     * @param $record_video_id
     * @return mixed
     */
    private function modifyRecordVideoCover($play_info, $record_video_id)
    {
        $json = Lecloud::getVideoImage($play_info['videoId']);
        //更新cover
        try {
            $arr = json_decode($json, true);
            $cover_url = str_replace('http://', 'https://proxy.daoapp.io/', $arr['data']['img1']);

            Playinfo::where('id', $record_video_id)->update([
                'cover' => $cover_url
            ]);

            $play_info['cover'] = $cover_url;
        } catch (\Exception $e) {
            $play_info['cover'] = null;
        }

        return $play_info;
    }

    /**
     * 获取直播详细信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getActivityInfo(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);

        $live_info = Liveinfo::select('uid', 'activityId', 'created_at')->where('id', $request->input('id'))->first();

        if (!$live_info) {
            return redirect()->route('admin_act_info');
        }

        $user = User::select('id', 'name', 'email')->where('id', $live_info['uid'])->first();
        $user_info = Userinfo::select('room_name', 'room_desc')->where('id', $live_info['uid'])->first();

        return view('admin.manage.actinfo', [
            'live_info' => $live_info,
            'user' => $user,
            'user_info' => $user_info
        ]);
    }

    /**
     * 停止直播
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postStopActivity(Request $request)
    {
        $live_info = Liveinfo::select('activityId')->where('uid', $request->input('uid'))->first();
        $activityId = $live_info->activityId;
        Lecloud::stopActivity($activityId);
        Liveinfo::where('uid', $request->input('uid'))->delete();

        return redirect()->route('admin_act_info');
    }

    /**
     * 用户封禁
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBlockUser(Request $request)
    {
        $this->validate($request,[
            'uid' => 'required|integer'
        ]);

        User::where('id', $request->input('uid'))->update(['status' => 3]);
        return redirect()->route('admin_users_normal');
    }

    /**
     * 用户通过审核
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUnblockUser(Request $request)
    {
        $this->validate($request,[
            'uid' => 'required|integer'
        ]);
        User::where('id', $request->input('uid'))->update(['status' => 1]);
        return redirect()->route('admin_users_blocked');
    }
}