<?php

namespace App\Http\Controllers\Api;

use App\Facades\Leancloud;
use App\Models\User;
use App\Models\Userinfo;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LiveController extends Controller
{
    public function __construct()
    {
        //
    }

    public function getLiveInfo(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);

        $id = $request->input('id');

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
        } else {
            $liveId = 'null';
        }

        $name = $user->name;
        $email = $user->email;
        $appId = Leancloud::getAppId();

        return response()->json([
            'title' => $title,
            'description' => $description,
            'name' => $name,
            'liveId' => $liveId,
            'avatar' => md5($email),
            'appId' => $appId,
            'roomId' => $roomId,
        ])->setCallback($request->input('callback'));
    }
}
