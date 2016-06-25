<?php

namespace App\Http\Controllers\Play;

use App\Facades\Lecloud;
use App\Models\Playinfo;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function __construct()
    {
        view()->share('title', '视频');
    }

    /**
     * 视频首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getVideoIndex()
    {
        return redirect()->route('video_ac_all');
    }

    /**
     * 全部录制视频
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRecordVideoAll()
    {
        //录制视频分页读取
        $record_videos = Playinfo::select('id', 'uid', 'name', 'cover', 'views', 'created_at')->orderBy('id', 'desc')->paginate(12);

        //获取用户信息
        $users = $users_id = null;
        foreach ($record_videos as $record_video) {
            $users_id[] = $record_video['uid'];
        }
        if($users_id){
            $users_info = User::select('id', 'name', 'email')->whereIn('id', $users_id)->get();
            //KV关联
            foreach ($users_info as $value) {
                $users[$value['id']]['name'] = $value['name'];
                $users[$value['id']]['email'] = $value['email'];
            }
        }

        return view('video.v.allac', [
            'title' => '全部直播视频',
            'record_videos' => $record_videos,
            'users' => $users
        ]);
    }

    /**
     * 录制视频播放单页
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRecordVideoPlay($id)
    {
        $video_info = Playinfo::select('id', 'uid', 'name', 'videoId', 'videoUnique', 'views', 'created_at')->where('id', $id)->first();

        if (!$video_info) {
            return response()->view('errors.404', [], 404);
        }
        //浏览量自增
        Playinfo::where('id', $id)->increment('views');
        //获取作者信息
        $author_info = User::select('name', 'email')->where('id', $video_info['uid'])->first();
        //获取uuid
        $uu = Lecloud::getUU();

        return view('video.v.record', [
            'title' => $video_info['name'],
            'video_info' => $video_info,
            'uu' => $uu,
            'author_info' => $author_info
        ]);
    }
}
