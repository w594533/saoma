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
      return view('Frontend.home');
    }

    public function showuploadimg()
    {
      $app = new Application(config('wechat'));
      $js = $app->js;

      $jssdk = '<script type="text/javascript" charset="utf-8">wx.config('.$js->config(array('uploadImage', 'chooseImage'), true).');</script>';
      return view('Frontend.uploadimg', compact('jssdk'));
    }

    public function uploadimg(Request $request)
    {
      $app = new Application(config('wechat'));

      // 临时素材
      $temporary = $app->material_temporary;

      $media_ids = $request->media_ids;
      return $media_ids;
      $files = '';
      foreach ($media_ids as $key => $media_id) {
        $filename = md5(md5(time().rand(1,9999)));
        $temporary->download($media_id, \Storage::disk('local').'/public/images/', $filename.".jpg");
        $files[] = '/public/images/', $filename.".jpg";
      }
      return $files;
    }
}
