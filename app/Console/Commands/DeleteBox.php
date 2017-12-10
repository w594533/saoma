<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Box;
use App\Services\OSS;

class DeleteBox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:box';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete 30 Days Ago Box';

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
      $prev_first_date = date('Y-m-d', strtotime(date('Y-m-01') . ' -1 month')).' 00:00:00';
      $prev_last_date = date('Y-m-d', strtotime(date('Y-m-01') . ' -1 day')).' 23:59:59';
      // $boxes = Box::where('status', 2)->get();
      $boxes = Box::where('status', 2)->whereBetween('created_at', [$prev_first_date, $prev_last_date])->get();
      foreach ($boxes as $key => $box) {
        //删除图片
        if ($box->image) {
          foreach (json_decode($box->image) as $image) {
            if ($image && file_exists(storage_path('app/public').'/'.$image)) {
              Storage::delete('public/'.$image);
            }
          }
        }

        //删除语音
        if ($box->voice && file_exists(storage_path('app/public/'.$box->voice))) {
            Storage::delete('public/'.$box->voice);
        }

        //删除二维码
        if ($box->qrcode && file_exists(storage_path('app/public/').$box->qrcode)) {
            Storage::delete('public/'.$box->qrcode);
        }

        //删除视频
        $bucketName = 'customer-saoma';
        if ($box->video_osskey) {
          OSS::publicDeleteObject($bucketName, $box->video_osskey);
        }

        $box->delete();
        // if (file_exists(storage_path('app/public/').$box->video)) {
        //     @unlink(storage_path('app/public/').$box->video);
        // }
        echo ".";
        flush();
      }
      echo 'done.';
    }
}
