<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    use ApiResponse;
    public function __construct()
    {
        $this->middleware('auth:api')->except('confirmMail');
    }

    //帐户邮件激动
    public function confirmMail($token)
    {
        $user=User::where('activation_token',$token)->firstOrFail();
        $user->activated=true;
        $user->activation_token=null;
        $user->save();

        return $this->message('帐户激活成功！');
    }

}
