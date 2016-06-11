<?php

namespace App\Http\Controllers\Play;

use App\Facades\Lecloud;
use App\Models\Playinfo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class VideoManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        view()->share('title', '视频管理');
    }

    /**
     * 视频管理首页
     * 注册用户可上传
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $uid = $this->getUserId();

        //获取录制视频列表
        $play_info_list = Playinfo::select('id', 'name', 'cover', 'created_at')->where('uid', $uid)->orderBy('id', 'desc')->limit(3)->get();

        return view('account.config.video', [
            'play_info_list' => $play_info_list
        ]);
    }

    /**
     * 获取全部录制视频
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRecordVideos()
    {
        $uid = $this->getUserId();
        //录制视频分页读取
        $record_videos = Playinfo::select('id', 'name', 'cover', 'created_at')->where('uid', $uid)->orderBy('id', 'desc')->paginate(12);

        return view('account.video.record.list', [
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
        $play_info = Playinfo::select('id', 'uid', 'name', 'activityId', 'ctime', 'videoId', 'videoUnique', 'cover')->where('id', $record_video_id)->first();
        //如果无数据或视频所属者错误则自动跳转
        if ((!$play_info) || Gate::denies('is-user-video', $play_info)) {
            return redirect()->route('record_all');
        }
        //无videoId自动更新
        if (!$play_info['videoId']) {
            $play_info = $this->modifyRecordVideoInfo($play_info, $record_video_id);
        }
        //有videoId无封面自动更新
        if ($play_info['videoId'] && (!$play_info['cover'])) {
            $play_info = $this->modifyRecordVideoCover($play_info, $record_video_id);
        }

        return view('account.video.record.manage', [
            'title' => '编辑视频信息',
            'play_info' => $play_info
        ]);
    }


    /**
     * 更新录制视频信息
     * @param $play_info
     * @param $record_video_id
     * @return mixed
     */
    protected function modifyRecordVideoInfo($play_info, $record_video_id)
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
                //录制视频超过24小时无数据删除
                if ((time() - $play_info['ctime']) > 86400) {
                    Playinfo::where('id', $record_video_id)->delete();
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
    public function modifyRecordVideoCover($play_info, $record_video_id)
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
     * 获取UID
     * @return mixed
     */
    protected function getUserId()
    {
        return Auth::user()->id;
    }
}
