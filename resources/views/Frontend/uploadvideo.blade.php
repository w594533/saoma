@extends('Frontend.layouts.default')
@section('css')
  <script src="/js/jquery.Huploadify.js"></script>
  <link rel="stylesheet" href="/css/Huploadify.css">
  <style>
    .content {width: auto; padding: 20px 10px;}
    .note {text-align: left; color: red; margin: 10px 0; font-size: 12px;}
    .note .red {display: block; padding:5px;}
    .content .uploadify-button {width: 120px; margin: 12px auto; display: block; text-align: center; font-weight: normal;}
    .uploadify-progress {display: block; width: 100%;}

    .up_filename, .progressnum, .delfilebtn, .uploadbtn, .up_percent {display: inline-block;font-size: 14px; margin-left: 0; margin-right: 20px; margin-top: 10px; color: #fff;}
    .up_filename {display: block; margin-top: 10px;}
    .uploadbtn, .delfilebtn {display: none;}
    .upload-btn button {display: block; min-width: 120px; margin: 10px auto;}
  </style>
@endsection
@section('content')
  {{ csrf_field() }}
<div class="video-box">

</div>
<div id="upload"></div>
<div class="upload-btn">
  <button class="button-upload-back btn btn-default btn-sm"><i class="fa fa-mail-reply"></i> 返回</button>
</div>
<div class="note">
    <span class="red">*允许格式：.mp4, .wma, .rmvb, .rm, .flash</span>
    <span class="red">*大小：30M</span>
</div>
<script>
$('#upload').Huploadify({
    auto:true,
    fileTypeExts:'*.jpg',
    multi:true,
    formData:{_token:$('input[name="_token"]').val()},
    fileSizeLimit:10*1024,
    showUploadedPercent:true,
    showUploadedSize:true,
    removeTimeout:9999999,
    'buttonText' : '<i class="fa fa-cloud-upload"></i> 上传视频',
    uploader:'{{route("uploadvideo")}}',
    uploadLimit: 1,
    onUploadStart:function(file){
        layer.open({  type: 2,content: '上传中', shadeClose: false });
        console.log(file.name+'开始上传');
    },
    onInit:function(obj){
        console.log('初始化');
        console.log(obj);
    },
    onUploadComplete:function(file){
        layer.closeAll();
        layer.open({
          shade: true,
          content: '上传成功',
          skin: 'msg',
          time: 2 //2秒后自动关闭
        });
        alert(file);
        console.log(file.name+'上传完成');
    },
    onCancel:function(file){
        console.log(file.name+'删除成功');
    },
    onClearQueue:function(queueItemCount){
        console.log('有'+queueItemCount+'个文件被删除了');
    },
    onDestroy:function(){
        console.log('destroyed!');
    },
    onSelect:function(file){
        console.log(file.name+'加入上传队列');
    },
    onQueueComplete:function(queueData){
        console.log('队列中的文件全部上传完成',queueData);
    }
});
$(".button-upload-back").on('click', function() {
    location.href="{{route('home')}}";
});
</script>
@endsection