<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontendController;
use EasyWeChat\Foundation\Application;
use App\Http\Middleware\CheckBox;
use App\Models\Box;


class HomeController extends FrontendController
{
    public function __construct()
    {
      parent::__construct();
    }

    public function show()
    {
      $this->_check_box();

      $oauth_user = session('wechat.oauth_user'); // 拿到授权用户资料
      //dd(session('ws.user'));
      $box = Box::find(session('ws.box')->id);
      return view('Frontend.home', compact('box'));
    }

}
