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
        //dd($this->getData());
        // 这段逻辑是从表格数据中取出需要导出的字段
        $directory = rand(1,9999).date("Ymd");
        Storage::makeDirectory($directory);
        //dd($this->getData());
        foreach ($this->getData() as $item) {
          $source_qrcode = storage_path('app/public').'/'.$item['qrcode'];
          if ($item['qrcode'] && file_exists($source_qrcode)) {
            $pathinfo = pathinfo($source_qrcode);
            $ext = $pathinfo['extension'];
            $filename = "qrcode_".date('Ymd')."_".$item['id'].".".$ext;
            Storage::copy('public/'.$item['qrcode'], '/'.$directory.'/'.$filename);
          }
        }

        //打包目录
        $zip = new ZipArchive();
        $zip_file = storage_path('app/').$directory.".zip";

        if($zip->open($zip_file, ZipArchive::OVERWRITE)=== TRUE){
            $this->addFileToZip(storage_path('app'), $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $zip->close(); //关闭处理的zip文件
        }

        if (file_exists($zip_file)) {
          Storage::deleteDirectory($directory);
          return response()->download($zip_file);
        } else {
          $error = new MessageBag([
              'title'   => '下载失败'
          ]);
          // 跳转页面
          Storage::deleteDirectory($directory);
          return redirect('/admin/boxes', compact('error'));
        }
    }

    private function addFileToZip($path,$zip){
      $handler=opendir($path); //打开当前文件夹由$path指定。
      while(($filename=readdir($handler))!==false){
        if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
          if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
            addFileToZip($path."/".$filename, $zip);
          }else{ //将文件加入zip对象
            $zip->addFile($path."/".$filename);
          }
        }
      }
      @closedir($path);
    }
}
