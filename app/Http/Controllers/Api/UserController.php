<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getInfo(Request $request){
        if($request->get('type')){
            switch ($request->get('type')){
                case 'name':
                    $return = ['name'=>Auth::user()->name];
                    break;
                default:
                    $return = ['error'=>'输入值不合法'];
            }
        }else{
            $return = ['error'=>'输入值不合法'];
        }

        return response()->json($return);
    }
}
