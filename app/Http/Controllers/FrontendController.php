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
    }

    protected function _check_box()
    {
      if (Request()->session()->exists('ws.box')) {
        //没有存储box非法进入
        abort(403,'对不起，您无权访问该页面！');
      } else {
        $user = session('ws.user');
        $box = session('ws.box');
        if (!$box->id) {
          abort(403,'对不起，您无权访问该页面！');
        }
        if ($box->user_id != $user->id) {
          abort(403,'对不起，您无权访问该页面！');
        }
      }
    }
}
