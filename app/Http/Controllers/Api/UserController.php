<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Userinfo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getInfo(Request $request)
    {
        if ($request->get('type')) {
            switch ($request->get('type')) {
                case 'name':
                    $return = ['name' => Auth::user()->name];
                    break;
                default:
                    $return = ['error' => '输入值不合法'];
            }
        } else {
            $return = ['error' => '输入值不合法'];
        }

        return response()->json($return)->setCallback($request->input('callback'));
    }

    public function getSettingUpdate()
    {
        return response('403 forbidden', 403);
    }

    /**
     * ajax更改User的setting
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSettingUpdate(Request $request)
    {
        //获取用户block状态
        if (Gate::denies('is-blocked')) {
            return redirect()->route('account');
        }
        //获取UID
        $uid = $this->getUserId();
        //判断request
        if ($request->has('room_name')) {
            //输入验证
            $this->validate($request, [
                'room_name' => 'required|string|max:255'
            ]);
            //数据库更新
            Userinfo::where('uid', $uid)->update([
                'room_name' => $request->input('room_name'),
            ]);

            $res = [
                'code' => 200,
                'info' => 'success'
            ];
        } else if ($request->has('room_desc')) {
            //输入验证
            $this->validate($request, [
                'room_desc' => 'required|string|max:255'
            ]);
            //数据库更新
            Userinfo::where('uid', $uid)->update([
                'room_desc' => $request->input('room_desc'),
            ]);

            $res = [
                'code' => 200,
                'info' => 'success'
            ];
        } else if ($request->has('long_desc')) {
            //输入验证
            $this->validate($request, [
                'long_desc' => 'required|string'
            ]);
            //数据库更新
            Userinfo::where('uid', $uid)->update([
                'long_desc' => $request->input('long_desc'),
            ]);

            $res = [
                'code' => 200,
                'info' => 'success'
            ];
        } else if ($request->has('category_id')) {
            //输入验证
            $this->validate($request, [
                'category_id' => 'required|integer|exists:category,id'
            ]);
            //数据库更新
            Userinfo::where('uid', $uid)->update([
                'category_id' => $request->input('category_id'),
            ]);

            $res = [
                'code' => 200,
                'info' => 'success'
            ];
        } else {
            $res = [
                'code' => 400,
                'info' => null
            ];
        }

        return response()->json($res);
    }

    public function getInfoUpdate()
    {
        return response('403 forbidden', 403);
    }

    public function postInfoUpdate(Request $request)
    {
        //获取UID
        $uid = $this->getUserId();
        //判断request
        if ($request->has('user_name')) {
            //输入验证
            $this->validate($request, [
                'user_name' => 'required|max:255|min:4'
            ]);
            //数据库更新
            User::where('id', $uid)->update([
                'name' => $request->input('user_name'),
            ]);

            $res = [
                'code' => 200,
                'info' => 'success'
            ];
        } else if ($request->has('user_email')) {
            //输入验证
            $this->validate($request, [
                'user_email' => 'required|email|max:255|unique:users'
            ]);
            //数据库更新
            User::where('id', $uid)->update([
                'email' => $request->input('user_email'),
            ]);

            $res = [
                'code' => 200,
                'info' => 'success'
            ];
        } else {
            $res = [
                'code' => 400,
                'info' => null
            ];
        }

        return response()->json($res);
    }

    protected function getUserId()
    {
        return Auth::user()->id;
    }
}
