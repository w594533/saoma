<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\MessageBag;
use App\Models\Box;
use DB;

class BoxGenerateController extends Controller
{
  public function index()
  {
      return Admin::content(function (Content $content) {

          $content->header('生成二维码');
          // $content->description('Description...');

          // $content->row(Dashboard::title());

          $form = new Form();
          $form->action('generate');

          $form->number('num', '数量')->help('每次最多生成100个');

          $content->row($form->render());
      });
  }

  public function generate(Request $request)
  {
    // $validator = Validator::make(...);
    // $validator = Validator::make($request->all(), [
    //   'num' => 'required|min:1|max:30',
    // ])->validate();

    $validator = Validator::make($request->all(), [
        'num' => 'required|integer|min:1|max:100'
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors();
        $error = new MessageBag([
            'title'   => '发生错误',
            'message' => $errors->first('num'),
        ]);
        return back()->with(compact('error'));
    } else {
        $success = new MessageBag([
          'title'   => intval($request->num).'张二维码生成成功'
        ]);
        $datas = array();
        if (intval($request->num) > 0) {
          for($i=0;$i<intval($request->num);$i++){
            $datas[$i] = [
              'created_at' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s"),
            ];
          }
          DB::table('boxes')->insert($datas);
        }
        return back()->with(compact('success'));
    }
  }

  /**
   * 文件下载
   * @param  Box    $box [description]
   * @return [type]      [description]
   */
  public function download(Box $box)
  {
    if (!$box || ($box && !$box->qrcode)) {
      $error = new MessageBag([
          'title'   => '暂无数据'
      ]);
      return back()->with(compact('error'));
    }

    $qrcode = storage_path('app/public').'/'.$box->qrcode;
    $pathinfo = pathinfo($qrcode);
    $ext = $pathinfo['extension'];
    if (file_exists($qrcode)) {
      return response()->download($qrcode, date('Ymd', strtotime($box->created_at))."-".$box->id.".".$ext);
    } else {
      $error = new MessageBag([
          'title'   => '文件不存在'
      ]);
      return back()->with(compact('error'));
    }
  }
}
