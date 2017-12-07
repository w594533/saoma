<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;


class HomeController extends Controller
{
    public function show()
    {
      // dd(config('wechat'));
      $user = session('wechat.oauth_user'); // 拿到授权用户资料
      return view('frontend.home');
    }

    public function showuploadimg()
    {
      $app = new Application(config('wechat'));
      $js = $app->js;

      $jssdk = '<script type="text/javascript" charset="utf-8">wx.config('.$js->config(array('uploadImage', 'chooseImage'), true).');</script>';
      return view('frontend.uploadimg', compact('jssdk'));
    }
}
