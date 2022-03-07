<?php

namespace App\Exceptions;

use App\Http\Controllers\Common\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        if($request->header('Accept')==='application/json' || !empty($request->header('authorization')) || !empty($request->header('Access-Token'))){
            if($this->isHttpException($exception)){
                return response()->json(Response::error($exception->getCode(),"HTTPエラー",$exception->getMessage()),$exception->getCode()?:400);
            }elseif($exception instanceof ValidationException){
                return response()->json(Response::error(406000,"バリデーションエラー",$exception->getMessage()),406);
            }elseif($exception instanceof AuthenticationException){
                $ja_message=[
                        "Unauthenticated."=>"認証できませんでした。"
                ];
                return response()->json(Response::error(401000,"認証失敗",isset($ja_message[$exception->getMessage()])?$ja_message[$exception->getMessage()]:$exception->getMessage()),401);
            }else{
                //return response()->json(Response::error($exception->getCode(),"エラー",$exception->getMessage()));
            }
        }
        return parent::render($request, $exception);
    }
}
