<?php

namespace App\Helpers;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionReport
{
    use ApiResponse;

    public $exception;

    public $request;

    protected $report;

    function __construct(Request $request, Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    public $doReport = [
        AuthenticationException::class => ['未授权',401],
        ModelNotFoundException::class => ['该模型未找到',404],
        MethodNotAllowedHttpException::class=>['该方法未经允许',404],
        NotFoundHttpException::class=>['该页面不存在',404],
    ];

    public function shouldReturn(){
        //if (! ($this->request->wantsJson() || $this->request->ajax())){
        //    return false;
        //}
        foreach (array_keys($this->doReport) as $report){
            if ($this->exception instanceof $report){
                $this->report = $report;
                return true;
            }
        }
        return false;
    }

    public static function make(Exception $e){
        return new static(\request(),$e);
    }


    public function report(){

        $message = $this->doReport[$this->report];
        return $this->failed($message[0],$message[1]);

    }
}