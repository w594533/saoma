<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontendController;
use EasyWeChat\Foundation\Application;
use App\Models\Box;
use Illuminate\Support\Facades\Storage;
use App\Services\OSS;
use App\Tools\Imgcompress;


class BoxController extends FrontendController
{
    private $bucketName;
    public function __construct()
    {
      parent::__construct();
      $this->bucketName = 'customer-saoma';
    }

    public function show(Request $request, Box $box)
    {
      $user = session('ws.user');

      $box = Box::find($box->id);
      if (!$box) {
        abort(404, '页面不存在！');
      }
      //return view('Frontend.view', compact('box'));
      if (!$box->user_id) {
        $box->user_id = $user->id;
        $box->status = 2;
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
        return view('Frontend.view', compact('box'));
      }
    }

    public function showuploadimg()
    {
      $this->_check_box();

      $app = new Application(config('wechat'));
      $js = $app->js;

      $jssdk = '<script type="text/javascript" charset="utf-8">wx.config('.$js->config(array('uploadImage', 'chooseImage'), false).');</script>';
      $box = Box::find(session('ws.box')->id);
      $images = json_decode($box->image)?json_decode($box->image):[];
      return view('Frontend.uploadimg', compact('jssdk', 'images'));
    }

    public function showuploadvoice()
    {
      $this->_check_box();

      $app = new Application(config('wechat'));
      $js = $app->js;

      $voices = array("startRecord","stopRecord","onVoiceRecordEnd","playVoice","pauseVoice","stopVoice","onVoicePlayEnd","uploadVoice","downloadVoice");

      $jssdk = '<script type="text/javascript" charset="utf-8">wx.config('.$js->config($voices, false).');</script>';
      $box = Box::find(session('ws.box')->id);
      return view('Frontend.uploadvoice', compact('jssdk','box'));
    }

    public function deleteuploadimg(Request $request)
    {
      $this->_check_box();
      $user = session('ws.user');
      $box = Box::find(session('ws.box')->id);
      if ($box->image) {
        foreach (json_decode($box->image) as $image) {
          if ($image && file_exists(storage_path('app/public').'/'.$image)) {
            @unlink(storage_path('app/public').'/'.$image);
          }
        }
      }

      $box->image = null;
      $box->save();

      return response()->json(['status' => 'ok'], 200);
    }

    //上传图片
    public function uploadimg(Request $request)
    {
        $this->_check_box();
        $user = session('ws.user');
        $box = Box::find(session('ws.box')->id);

        $image_ossurl = array();
        $imgcompress_files = array();
        //上传新图
        if ($request->imgs) {
            //上传图片
            $i = 1;
            @mkdir(storage_path('app/public').'/upload/'.$user->id.'/', 0777, true);
            foreach ($request->imgs as $key => $post_image) {
              //最多上传3张
              if ($i > 4) {
                  break;
              }

              if ($post_image && preg_match('/^(data:\s*image\/(\w+);base64,)/', $post_image, $result)) {
                  $avatar_images_decode = str_replace($result[1], '', $post_image);
                  $ext = isset($result[2]) && $result[2] ? $result[2] : "jpg";
                  $md5name = md5(time() . rand(1, 9999) . $box->id . $key);
                  $newname = $md5name . "." . $ext;
                  $new_file = storage_path('app/public').'/upload/'.$user->id.'/'.$newname;

                  if (file_put_contents($new_file, base64_decode($avatar_images_decode))) {
                      $image_ossurl[] = 'upload/'.$user->id.'/'.$newname;
                      $i++;

                      //图片压缩
                      $imgcompress = new Imgcompress($new_file, 0.5);

                      $imgcompress_name = md5(time() . rand(1, 9999) . $key) . "." . $ext;
                      $imgcompress_file = storage_path('app/public').'/upload/'.$user->id.'/'.$imgcompress_name;
                      $imgcompress->compressImg($imgcompress_file);
                      $imgcompress_files[] = 'upload/'.$user->id.'/'.$imgcompress_name;

                      @unlink($new_file);
                  }
              }
            }

            if ($box->image) {
              foreach (json_decode($box->image) as $image) {
                if ($image && file_exists(storage_path('app/public').'/'.$image)) {
                  @unlink(storage_path('app/public').'/'.$image);
                }
              }
            }

            $box->image = json_encode($imgcompress_files);
            $box->save();
        }
        return response()->json(['status'=>'ok', 'data' => $image_ossurl], 200);
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
        $last_filename = $filename;

        $box = Box::find(session('ws.box')->id);
        //删除之前的视频文件
        if ($box->voice && file_exists(storage_path('app/public').'/'.$box->voice)) {
          @unlink(storage_path('app/public').'/'.$box->voice);
        }
        $result_file = $temporary->download($media_id, storage_path('app/public').'/upload/'.$user->id.'/', $filename);
        $source_voice = storage_path('app/public').'/upload/'.$user->id.'/'.$result_file;
        $dest_voice = storage_path('app/public').'/upload/'.$user->id.'/'.$last_filename.".mp3";

        //语音文件转换
        // exec('ffmpeg -i 1.amr 1.mp3');
        exec('ffmpeg -i '.$source_voice.' '.$dest_voice);
        $file = 'upload/'.$user->id.'/'.$last_filename.".mp3";
        $box->voice = $file;
        $box->save();

        //删除转换前的文件
        @unlink($source_voice);
        return response()->json(['status' => 'ok', 'data' => Storage::url($file)], 200);
    }

    public function showuploadtext()
    {
      $this->_check_box();

      $box = Box::find(session('ws.box')->id);
      return view('Frontend.uploadtext', compact('box'));
    }

    public function uploadtext(Request $request)
    {
      $this->_check_box();
      $box = Box::find(session('ws.box')->id);
      $text = $request->body;
      $box->body = $text;
      $box->save();
      return response()->json($text, 200);
    }

    public function showuploadvideo()
    {
      $this->_check_box();
      $box = Box::find(session('ws.box')->id);
      if(preg_match("/\x20*https?\:\/\/.*/i",$box->video)) {
        $video_url = $box->video;
      } else {
        $video_url = Storage::url($box->video);
      }
      return view('Frontend.uploadvideo', compact('box', 'video_url'));
    }

    public function uploadvideo(Request $request)
    {
      $this->_check_box();

      if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $box = Box::find(session('ws.box')->id);


        $user = session('ws.user');

        $photo = $request->file('file');
        $extension = $photo->extension();

        $filename = md5(md5(time().rand(1,9999)));

        # 上传到本地
        // @mkdir(storage_path('app/public').'/upload/'.$user->id.'/', 0777, true);
        // $store_result = $photo->storeAs('public/upload/'.$user->id, $filename.'.'.$extension);
        // // $output = [
        // //     'extension' => $extension,
        // //     'store_result' => $store_result
        // // ];
        // $save_path = 'upload/'.$user->id.'/'.$filename.'.'.$extension;

        # 上传到oss
        // 在外网上传一个文件并指定 options 如：Content-Type 类型
        // 更多 options 见：https://github.com/johnlui/AliyunOSS/blob/master/src/oss/src/Aliyun/OSS/OSSClient.php#L142-L148
        $res = OSS::publicUpload($this->bucketName, 'videos/'.$filename.'.'.$extension, $photo, [
            // 'ContentType' => 'video/mpeg4',
        ]);

        //删除之前视频
        if ($box->video_osskey) {
          OSS::publicDeleteObject($this->bucketName, $box->video_osskey);
        }
        // if ($box->video && file_exists(storage_path('app/public').'/'.$box->video)) {
        //   @unlink(storage_path('app/public').'/'.$box->video);
        // }
        $box->video = OSS::getPublicObjectURL($this->bucketName, 'videos/'.$filename.'.'.$extension);
        $box->video_osskey = 'videos/'.$filename.'.'.$extension;
        $box->save();
        return response()->json($res, 200);
      }
      exit('未获取到上传文件或上传过程出错');
    }
}
