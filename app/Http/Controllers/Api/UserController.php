<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;

class UserController extends Controller
{
    use ApiResponse,AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('auth:api')
            ->except('login','create');
    }

    //注册
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|between:5,32',
        ]);

        if ($validator->fails()) {
            $request->request->add([
                'errors' => $validator->errors()->toArray(),
                'code' => 401,
            ]);
            return $this->setStatusCode($request['code'])->failed($request['errors']);
        }
        $user=User::create([
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);
        if(!$user){
            return $this->failed('注册失败');
        }
        //发送邮件
        $this->sendEmailConfirmationTo($user);
        //生成token
        $response=$this->authenticateClient($request);

        return $this->success($response);
    }

    //登录
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|between:5,32',
        ]);

        if ($validator->fails()) {
            $request->request->add([
                'errors' => $validator->errors()->toArray(),
                'code' => 401,
            ]);
            return $this->setStatusCode($request['code'])->failed($request['errors']);
        }
        //拿到帐号密码
        $credentials = $this->credentials($request);
        //判断是否登录成功
        if($this->guard('api')->attempt($credentials)){
            if(!Auth::user()->activated){
                return $this->failed('当前用户邮件未激活',401);
            }
            $token=$this->authenticateClient($request);
            return $this->success($token);
        }
        return $this->failed('用户或者密码错误');
    }




    public function show(Request $request)
    {
        dd(Auth::user());
        dd('show');
    }

    public function update(Request $request, $id)
    {
        dd('update');
    }


    /**调用认证接口获取授权码
     * @param Request $request
     * @return data  授权码数据
     */
    protected function authenticateClient(Request $request)
    {
        // 个人感觉通过.env配置太复杂，直接从数据库查更方便
        $password_client = Client::query()->where('password_client',1)->latest()->first();

        $http = new  \GuzzleHttp\Client();
        $response = $http->post(config('app.url').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $password_client->id,
                'client_secret' => $password_client->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
    }

    //发送注册邮件
    public function sendEmailConfirmationTo($user)
    {
        $view="emails.confirm";
        $data=compact('user');
        $from=config('mail.from.address');
        $name=config('mail.from.name');
        $to=$user->email;
        $subject='感谢注册简叔！请确认你的邮箱。';

        Mail::send($view,$data,function($message)use($from,$name,$to,$subject){
            $message->from($from,$name)->to($to)->subject($subject);
        });

    }

}
