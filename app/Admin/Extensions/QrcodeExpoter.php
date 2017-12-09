<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use ZipArchive;

class QrcodeExpoter extends AbstractExporter
{
    public function export()
    {

        // 这段逻辑是从表格数据中取出需要导出的字段
        $directory = 'public/zip/'.rand(1,9999).date("Ymd");

        foreach ($this->getData() as $item) {
          $source_qrcode = storage_path('app/public').'/'.$item['qrcode'];
          if ($item['qrcode'] && file_exists($source_qrcode)) {
            $pathinfo = pathinfo($source_qrcode);
            $ext = $pathinfo['extension'];
            $filename = "qrcode_".date('Ymd')."_".$item['id'].".".$ext;
            Storage::copy('public/'.$item['qrcode'], $directory.'/'.$filename);
          }
        }

        //打包目录
        $zip_file = storage_path('app/').$directory.".zip";
        $zipper = new \Chumper\Zipper\Zipper;
        $zipper->make($zip_file)->add(storage_path('app/').$directory);
        $zipper->close();

        if (file_exists($zip_file)) {
          Storage::deleteDirectory($directory);
          header("Cache-Control: public");
          header("Content-Description: File Transfer");
          header('Content-Disposition: attachment; filename='.basename($zip_file)); //文件名
          header("Content-Type: application/zip"); //zip格式的
          header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
          header('Content-Length: '. filesize($zip_file)); //告诉浏览器，文件大小
          @readfile($zip_file);
          exit('download ok');
        } else {
          Storage::deleteDirectory($directory);
          exit('download error');
        }
    }
}
