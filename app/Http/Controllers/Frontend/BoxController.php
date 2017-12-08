<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontendController;
use EasyWeChat\Foundation\Application;
use App\Models\Box;
use Illuminate\Support\Facades\Storage;


class BoxController extends FrontendController
{
    public function __construct()
    {
      parent::__construct();
    }

    public function show(Request $request, Box $box)
    {
      $user = session('ws.user');

      $box = Box::find($box->id);
      if (!$box->user_id) {
        $box->user_id = $user->id;
        $box->save();
        $request->session()->put('ws.box', $box);
        return redirect()->route('home');
      }
      if ($box->user_id == $user->id) {
        //一致，进入首页，上传
        $request->session()->put('ws.box', $box);
        return redirect()->route('home');
      } else {
        //不一致，查看资料

      }
    }

    public function showuploadimg()
    {
      $this->_check_box();

      $app = new Application(config('wechat'));
      $js = $app->js;

      $jssdk = '<script type="text/javascript" charset="utf-8">wx.config('.$js->config(array('uploadImage', 'chooseImage'), true).');</script>';
      $box = session('ws.box');
      $images = json_decode($box->image)?json_decode($box->image):[];
      return view('Frontend.uploadimg', compact('jssdk', 'images'));
    }

    public function showuploadvoice()
    {
      $this->_check_box();

      $app = new Application(config('wechat'));
      $js = $app->js;

      $voices = array("startRecord","stopRecord","onVoiceRecordEnd","playVoice","pauseVoice","stopVoice","onVoicePlayEnd","uploadVoice","downloadVoice");

      $jssdk = '<script type="text/javascript" charset="utf-8">wx.config('.$js->config($voices, true).');</script>';
      $box = session('ws.box');
      return view('Frontend.uploadvoice', compact('jssdk','box'));
    }

    //上传图片
    public function uploadimg(Request $request)
    {
      $this->_check_box();

      $user = session('ws.user');
      $app = new Application(config('wechat'));
      // 临时素材
      $temporary = $app->material_temporary;
      $media_ids = $request->media_ids;
      $media_ids = explode(",", $media_ids);
      $files = [];
	    @mkdir(storage_path('app/public').'/upload/'.$user->id.'/', 0777, true);
      foreach ($media_ids as $key => $media_id) {
        $filename = md5(md5(time().rand(1,9999)));
        $temporary->download($media_id, storage_path('app/public').'/upload/'.$user->id.'/', $filename.".jpg");
        $files[] = 'upload/'.$user->id.'/'.$filename.'.jpg';
      }

      $box = session('ws.box');
      //删除之前的文件
      if ($box->image) {
        foreach (json_decode($box->image) as $image) {
          if ($image && file_exists(storage_path('app/public').'/'.$image)) {
            @unlink(storage_path('app/public').'/'.$image);
          }
        }
      }
      $box->image = json_encode($files);
      $box->save();

      return response()->json($files, 200);
    }

    //上传语音
    public function uploadvoice(Request $request)
    {
        $this->_check_box();

        $user = session('ws.user');
        $app = new Application(config('wechat'));
        // 临时素材
        $temporary = $app->material_temporary;
        $media_id = $request->media_id;
        @mkdir(storage_path('app/public').'/upload/'.$user->id.'/', 0777, true);
        $filename = md5(md5(time().rand(1,9999)));

        $box = session('ws.box');
        //删除之前的视频文件
        if ($box->voice && file_exists(storage_path('app/public').'/'.$box->voice)) {
          @unlink(storage_path('app/public').'/'.$box->voice);
        }

        $temporary->download($media_id, storage_path('app/public').'/upload/'.$user->id.'/', $filename);
        $file = 'upload/'.$user->id.'/'.$filename;
        $box->voice = $file;
        $box->save();
        return response()->json($file, 200);
    }

    public function showuploadtext()
    {
      $this->_check_box();

      $box = session('ws.box');
      return view('Frontend.uploadtext', compact('box'));
    }

    public function uploadtext(Request $request)
    {
      $this->_check_box();
      $box = session('ws.box');
      $text = $request->body;
      $box->body = $text;
      $box->save();
      return response()->json($text, 200);
    }

    public function showuploadvideo()
    {
      $this->_check_box();
      $box = session('ws.box');
      return view('Frontend.uploadvideo', compact('box'));
    }

    public function uploadvideo(Request $request)
    {
      $this->_check_box();

      if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $box = session('ws.box');
        //删除之前的视频文件
        if ($box->video && file_exists(storage_path('app/public').'/'.$box->video)) {
          @unlink(storage_path('app/public').'/'.$box->video);
        }
        $user = session('ws.user');

        $photo = $request->file('file');
        $extension = $photo->extension();
        @mkdir(storage_path('app/public').'/upload/'.$user->id.'/', 0777, true);
        $filename = md5(md5(time().rand(1,9999)));
        $store_result = $photo->storeAs('public/upload/'.$user->id, $filename.'.'.$extension);
        // $output = [
        //     'extension' => $extension,
        //     'store_result' => $store_result
        // ];
        $box->video = 'upload/'.$user->id.'/'.$filename.'.'.$extension;
        $box->save();
      }
      exit('未获取到上传文件或上传过程出错');
    }
}
