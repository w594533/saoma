<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;

class FrontendController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
      $oauth_user = session('wechat.oauth_user'); // 拿到授权用户资料
      $user = User::where('openid', $oauth_user->id)->first();
      if (!$user) {
        $user = new User;
        $user->openid = $oauth_user->id;
        $user->name = $oauth_user->name;
        $user->gender = $oauth_user->original['sex'];
        $user->email = $oauth_user->email;
        $user->avatar = $oauth_user->avatar;
        $user->save();
      }
      session(['ws.user' => $user]);
    }
}
