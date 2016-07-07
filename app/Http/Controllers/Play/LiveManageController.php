<?php

namespace App\Http\Controllers\Play;

use App\Facades\Lecloud;
use App\Models\Category;
use App\Models\Liveinfo;
use App\Models\Playinfo;
use App\Models\Userinfo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LiveManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        view()->share('title', '直播管理');
    }

    public function getIndex()
    {
        //获取用户block状态
        if (Gate::denies('is-blocked')) {
            return redirect()->route('account');
        }
        //获取uid
        $uid = $this->getUserId();
        //获取直播状态
        if ($this->getLiveStatus()) {
            return view('account.config.live.stop', [
                'title' => '结束直播'
            ]);
        } else {
            //获取个人信息
            $room_info = Userinfo::select('room_name', 'room_desc', 'category_id')->where('uid', $uid)->first();
            //获取分类信息
            $categories = Category::select('id', 'name')->get();

            $room_name = $room_info['room_name'];
            $room_desc = $room_info['room_desc'];
            $category_id = $room_info['category_id'];

            return view('account.config.live.create', [
                'title' => '创建直播',
                'room_name' => $room_name,
                'room_desc' => $room_desc,
                'category_id' => $category_id,
                'categories' => $categories
            ]);
        }
    }

    /**
     * 创建直播
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(Request $request)
    {
        //获取用户权限
        if (Gate::denies('is-blocked')) {
            return redirect()->route('account');
        }
        //获取UID
        $uid = Auth::user()->id;

        if ($this->getLiveStatus()) {
            return redirect()->route('live_manage');
        }

        //验证输入数据
        $this->validate($request, [
            'category_id' => 'required|integer|exists:category,id'
        ]);

        $config['livename'] = $request->input('live_name') ? $request->input('live_name') : $request->user()->name . '的直播';
        $config['livedes'] = $request->input('live_des') ? $request->input('live_des') : '暂无简介';
        $config['record'] = $request->input('record') ? 1 : 0;
        $config['codeRateTypes'] = '99';
        if ($request->input('rate4'))
            $config['codeRateTypes'] .= ',25';
        if ($request->input('rate3'))
            $config['codeRateTypes'] .= ',19';
        if ($request->input('rate2'))
            $config['codeRateTypes'] .= ',16';
        if ($request->input('rate1'))
            $config['codeRateTypes'] .= ',13';
        //更新userinfo
        Userinfo::where('uid', $uid)->update([
            'room_name' => $config['livename'],
            'room_desc' => $config['livedes'],
            'category_id' => $request->input('category_id')
        ]);

        $result = Lecloud::creatActivity($config);
        $res_arr = json_decode($result, true);
        $activityId = array_key_exists('activityId', $res_arr) ? $res_arr['activityId'] : false;
        if ($activityId) {
            $Liveinfo = new Liveinfo();
            $Liveinfo->uid = Auth::user()->id;
            $Liveinfo->ctime = time();
            $Liveinfo->category_id = $request->input('category_id');
            $Liveinfo->title = $config['livename'];
            $Liveinfo->description = $request->input('livedes') ? $request->input('livedes') : '暂无简介';
            $Liveinfo->activityId = $activityId;
            $Liveinfo->liveId = $this->getLiveKey($activityId, false);
            if ($Liveinfo->save()) {
                if ($config['record']) {
                    $Playinfo = new Playinfo();
                    $Playinfo->uid = Auth::user()->id;
                    $Playinfo->name = $config['livename'];
                    $Playinfo->activityId = $activityId;
                    $Playinfo->ctime = time();
                    $Playinfo->save();
                }
            }
        }

        return redirect()->route('live_manage');
    }

    /**
     * 停止直播
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postStop()
    {
        //获取用户block状态
        if (Gate::denies('is-blocked')) {
            return redirect()->route('account');
        }
        //获取直播状态
        if (!$this->getLiveStatus()) {
            return redirect()->route('live_manage');
        }
        //获取直播activity信息
        $live_info = Liveinfo::select('activityId')->where('uid', Auth::user()->id)->first();
        $activityId = $live_info['activityId'];
        Lecloud::stopActivity($activityId);
        if (Liveinfo::where('uid', Auth::user()->id)->delete()) {
            return redirect()->route('live_manage');
        } else {
            return redirect()->to('live_manage');
        }
    }

    /**
     * 获取直播推流地址
     * @param bool $need_res
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getPushUrl($need_res = true)
    {
        //获取用户block状态
        if (Gate::denies('is-blocked')) {
            return redirect()->route('account');
        }

        if (!$this->getLivestatus()) {
            return redirect()->route('live_manage');
        }

        //获取推流地址
        $live_info = Liveinfo::select('activityId')->where('uid', Auth::user()->id)->first();
        $activityId = $live_info->activityId;
        //获取推流码
        $pushKey = $this->getLiveKey($activityId);

        $res = ['pushUrl' => $pushKey];
        return response()->json($res);
    }

    /**
     * 获取推流码
     * @param $activityId
     * @param bool $need_res
     * @return mixed
     */
    public function getLiveKey($activityId, $need_res = true)
    {
        $json = Lecloud::getPushUrl($activityId);
        $arr = json_decode($json, true);
        $pushAll = array_key_exists('lives', $arr) ? $arr['lives'][0]['pushUrl'] : null;
        $pushKey = str_replace('rtmp://w.gslb.lecloud.com/live/', '', $pushAll);

        if ($need_res) {
            return $pushKey;
        } else {
            //返回直播ID
            $pattern = '/(.+)\?sign=/';
            preg_match($pattern, $pushKey, $matches);
            $liveId = $matches[1];
            return $liveId;
        }
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
        $live_status = Liveinfo::where('uid', $this->getUserId())->count();
        return $live_status;
    }
}
