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
          $user->name = $oauth_user->name;
          $user->email = $oauth_user->email;
          $user->avatar = $oauth_user->avatar;
          $user->save();
        }
        session(['ws.user' => $user]);
        return $next($request);
    }
}
