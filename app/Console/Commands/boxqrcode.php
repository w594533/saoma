<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Box;
use QrCode;

class boxqrcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boxqrcode:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Box Qrcode Generate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $boxs = Box::where('status', '=', 1)
                ->whereNull('qrcode')
                ->whereNull('user_id')->take(20)->get();
      foreach ($boxs as $key => $box) {
        //生成一个中间有LOGO图片的二维码
        $url = config('app.url')."/box/".$box->id;
        @mkdir(storage_path('app/public/upload/qrcode'), 0777, true);
        $file = 'upload/qrcode/'.md5(rand(1,9999).time().$box->id).".png";
        $save_path = storage_path('app/public/').$file;
        $fp=fopen($save_path,"w+");
        QrCode::format('png')->size(200)->merge('/public/img/logo.png')->margin(2)->generate($url, $save_path);
        if (file_exists($save_path)) {
          $box->qrcode = $file;
          $box->save();
        }
        fclose($fp);
        echo ".";
        flush();
      }
      echo "done";
    }
}
