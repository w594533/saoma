<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class CheckBox
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $oauth_user = session('wechat.oauth_user'); // 拿到授权用户资料
        $user = User::where('openid', $oauth_user->id)->first();
        if (!$user) {
          $user = new User;
          $user->openid = $oauth_user->id;
          $user->name = $oauth_user->nickname;
          $user->gender = $oauth_user->original['sex'];
          $user->email = $oauth_user->email;
          $user->avatar = $oauth_user->avatar;
          $user->save();
        } else {
          $user->name = $oauth_user->nickname;
          $user->gender = $oauth_user->original['sex'];
          $user->email = $oauth_user->email;
          $user->avatar = $oauth_user->avatar;
          $user->save();
        }
        session(['ws.user' => $user]);

        $box = $request->box;
        $box = Box::find($box->id);
        if (!$box) {
          abort(404, '页面不存在！');
        }
        if (!$box->user_id) {
          $box->user_id = $user->id;
          $box->status = 2;
          $box->save();
        }
        session(['ws.box' => $box]);
        return $next($request);
    }
}
