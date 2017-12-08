<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontendController;
use EasyWeChat\Foundation\Application;


class HomeController extends FrontendController
{
    public function __construct()
    {
      parent::__construct();
    }

    public function show()
    {
      $oauth_user = session('wechat.oauth_user'); // 拿到授权用户资料
      //dd(session('ws.user'));
      return view('Frontend.home');
    }

    public function showuploadimg()
    {
      $app = new Application(config('wechat'));
      $js = $app->js;

      $jssdk = '<script type="text/javascript" charset="utf-8">wx.config('.$js->config(array('uploadImage', 'chooseImage'), true).');</script>';
      return view('Frontend.uploadimg', compact('jssdk'));
    }

    public function showuploadvoice()
    {
      $app = new Application(config('wechat'));
      $js = $app->js;

      $voices = array("startRecord","stopRecord","onVoiceRecordEnd","playVoice","pauseVoice","stopVoice","onVoicePlayEnd","uploadVoice","downloadVoice");

      $jssdk = '<script type="text/javascript" charset="utf-8">wx.config('.$js->config($voices, true).');</script>';
      return view('Frontend.uploadvoice', compact('jssdk'));
    }

    //上传图片
    public function uploadimg(Request $request)
    {
      $user = session('ws.user');
      $app = new Application(config('wechat'));
      // 临时素材
      $temporary = $app->material_temporary;
      $media_ids = $request->media_ids;
      $media_ids = explode(",", $media_ids);
      $files = '';
	    @mkdir(storage_path('app/public').'/upload/'.$user->id.'/', 0777, true);
      foreach ($media_ids as $key => $media_id) {
        $filename = md5(md5(time().rand(1,9999)));
        $temporary->download($media_id, storage_path('app/public').'/upload/'.$user->id.'/', $filename.".jpg");
        $files[] = '/upload/'.$user->id.'/'.$filename.'.jpg';
      }
      return response()->json($files, 200);
    }

    //上传语音
    public function uploadvoice(Request $request)
    {
        $user = session('ws.user');
        $app = new Application(config('wechat'));
        // 临时素材
        $temporary = $app->material_temporary;
        $media_id = $request->media_id;
        @mkdir(storage_path('app/public').'/upload/'.$user->id.'/', 0777, true);
        $filename = md5(md5(time().rand(1,9999)));
        $temporary->download($media_id, storage_path('app/public').'/upload/'.$user->id.'/', $filename);
        $file = '/upload/'.$user->id.'/'.$filename;
        return response()->json($file, 200);
    }

    public function showuploadtext()
    {
      return view('Frontend.uploadtext');
    }

    public function uploadtext(Request $request)
    {
      $text = $request->body;
      return response()->json($text, 200);
    }

    public function showuploadvideo()
    {
      return view('Frontend.uploadvideo');
    }

    public function uploadvideo(Request $request)
    {
      if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $user = session('ws.user');
        
        $photo = $request->file('file');
        $extension = $photo->extension();
        @mkdir(storage_path('app/public').'/upload/'.$user->id.'/', 0777, true);
        $filename = md5(md5(time().rand(1,9999)));
        $store_result = $photo->storeAs('public/upload/'.$user->id, $filename.'.'.$extension);
        $output = [
            'extension' => $extension,
            'store_result' => $store_result
        ];
        print_r($output);exit();
      }
      exit('未获取到上传文件或上传过程出错');
    }
}
