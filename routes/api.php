<?php

use App\Http\Controllers\Account\PasswordController;
use App\Http\Controllers\Account\User;
use App\Http\Controllers\Account\VirtualController;
use App\Http\Controllers\Auth\PasswordReset;
use App\Http\Controllers\Auth\VerifyEmail;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\FriendController;
use App\Http\Controllers\Chat\MessageController;
use App\Http\Controllers\Chat\MemberController;
use App\Http\Controllers\Chat\RoomController;
use App\Http\Controllers\Common\AuthController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\File\IconController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Recipe\CategoryController;
use App\Http\Controllers\Recipe\ItemController;
use App\Http\Controllers\Recipe\RecipeController;
use App\Http\Controllers\Recipe\UnitController;
use App\Http\Middleware\original\auth;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//ユーザー認証なし
Route::group(['middleware'=>['api','CannotParams']],function(){
    Route::group(['prefix'=>'auth'],function(){
        //ユーザー認証
        Route::middleware([auth::class])->post('/',[AccessTokenController::class,'issueToken']);
        //メールアドレス認証
        Route::post('email',[VerifyEmail::class,'store']);
        //パスワードリセット
        Route::apiResource('reset',PasswordReset::class)->only(['store','show','update','delete']);
    });
    //ユーザー作成
    Route::apiResource('user',User::class)->only('store');
});
//ユーザー認証あり
Route::group(['middleware'=>['api','auth:api','CannotParams']],function(){
    Route::get('auth/info/{type}',[AuthController::class,"show"]);
    //ユーザーアカウントシステム
    Route::group(['prefix'=>'user'],function(){
        //ユーザーアカウント
        Route::apiResource('',User::class)->only('index');
        Route::put('{user}',[User::class,'update']);
        Route::delete('{user}',[User::class,'destroy']);
        //パスワード
        Route::apiResource('password',PasswordController::class)->only(['store','update']);
        Route::apiResource('virtual',VirtualController::class);
    });
    //プロフィールシステム
    Route::group(['prefix'=>'profile'],function(){
        Route::get('{profile}',[ProfileController::class,'show']);
    });
    //ファイル管理システム
    Route::group(['prefix'=>'file'],function(){
        Route::get('',[FileController::class,'index']);
        Route::put('{file}',[FileController::class,'update']);
        Route::delete('{file}',[FileController::class,'destroy']);
    });
    //アイコン管理システム
    Route::group(['prefix'=>'icon'],function(){
        Route::get('',[IconController::class,'index']);
        Route::put('{file}',[IconController::class,'update']);
        Route::delete('{file}',[IconController::class,'destroy']);
    });
    //チャットシステム
    Route::group(['prefix'=>'chat'],function(){
        Route::apiResource('',ChatController::class)->only('index');
        Route::apiResource('room',RoomController::class);
        Route::apiResource('friend',FriendController::class);
        Route::apiResource('room/{room}/member',MemberController::class);
        Route::apiResource('room/{room}/message',MessageController::class);
    });
    //レシピ保存システム
    Route::group(['prefix'=>'recipe'],function(){
        Route::apiResource('category',CategoryController::class);
        Route::apiResource('category/{category}/item',ItemController::class);
        Route::apiResource('unit',UnitController::class)->only('index');
        Route::apiResource('',RecipeController::class)->only(['index','store']);
        Route::get('{recipe}',[RecipeController::class,'show']);
        Route::put('{recipe}',[RecipeController::class,'update']);
        Route::delete('{recipe}',[RecipeController::class,'delete']);
    });
});
//ユーザー認証なし
Route::group(['middleware'=>['api','CannotParams']],function(){
    //ファイルアップロード
    Route::middleware(['file'])->post('/',[FileController::class,'store']);
    Route::middleware(['icon'])->post('/icon',[IconController::class,'store']);
});