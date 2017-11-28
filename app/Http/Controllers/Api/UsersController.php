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

class UsersController extends Controller
{
    use ApiResponse,AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('auth:api')
            ->except('login','create','refreshToken');
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
            //邮箱是否激活
            if(!Auth::user()->activated){
                return $this->failed('当前用户邮件未激活',401);
            }
            $token=$this->authenticateClient($request);
            return $this->success($token);
        }
        return $this->failed('用户或者密码错误');
    }

    //查看个人信息 GET
    public function show(User $user)
    {
        return $this->success($user->toArray());
    }

    //更新个人信息 put
    public function update(User $user,Request $request)
    {
        $this->authorize('update',$user);
        $user->email=$request->email;
        $user->save();
        return $this->message('修改成功!');
    }


    /**调用认证接口获取授权码
     * @param Request email
     * @param Request password
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

    /**刷新授权码
     * @param Request refresh_token
     * @return mixed
     */
    public function refreshToken(Request $request)
    {
        $password_client = Client::query()->where('password_client',1)->latest()->first();

        $http = new  \GuzzleHttp\Client();
        $response = $http->post(config('app.url').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => $password_client->id,
                'client_secret' => $password_client->secret,
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
