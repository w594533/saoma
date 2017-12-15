@extends('Frontend.layouts.default')
@section('css')
  <link rel="stylesheet" href="/css/Huploadify.css">
  <style>
  .upload-btn .hide {display: none;}
  .upload-btn button {display: block; min-width: 120px; margin: 10px auto;}
  .content .uploadify-button {width: 120px; margin: 12px auto; display: block; text-align: center; font-weight: normal;}
  .uploadify-progress {display: block; width: 100%;}
  .up_filename, .progressnum, .delfilebtn, .uploadbtn, .up_percent {display: inline-block;font-size: 14px; margin-left: 0; margin-right: 20px; margin-top: 10px; color: #fff;}
  .up_filename {display: block; margin-top: 10px;}
  .uploadbtn, .delfilebtn {display: none;}
  </style>
@endsection
@section('js')
  <script src="/js/jquery.Huploadify.js"></script>
@endsection
@section('content')
<div class="img-box">
  @foreach ($images as $key => $image)
    <img src="{{Storage::url($image)}}" width="100" height="100"/>
  @endforeach
</div>
{{ csrf_field() }}
<div id="upload"></div>
<div class="upload-btn">
  {{-- <button class="button-select-img btn btn-default btn-sm"><i class="fa fa-save"></i> <span class="text">选择图片</span></button> --}}
  {{-- <button class="button-upload-img btn btn-default btn-sm"><i class="fa fa-cloud-upload"></i> <span class="text">开始上传</span></button> --}}
  <button class="button-upload-back btn btn-default btn-sm"><i class="fa fa-mail-reply"></i> <span class="text">返回</span></button>
</div>

<script>
$(function() {
  $('#upload').Huploadify({
      auto:true,
      fileTypeExts:'*.jpg;*.jpeg;*.png',
      multi:true,
      formData:{_token:$('input[name="_token"]').val()},
      fileSizeLimit:30*1024,
      showUploadedPercent:true,
      showUploadedSize:true,
      removeTimeout:9999999,
      'buttonText' : '<i class="fa fa-cloud-upload"></i> 上传图片',
      uploader:'{{route("uploadimg")}}',
      'uploadLimit': 4,
      // multi: true,
      onUploadStart:function(file){
          layer.open({  type: 2,content: '上传中', shadeClose: false });
          console.log(file.name+'开始上传');
      },
      onInit:function(obj){
          console.log('初始化');
          console.log(obj);
      },
      onUploadComplete:function(file){
        // alert(JSON.stringify(file));
          // layer.closeAll();
          // layer.open({
          //   shade: true,
          //   content: '上传成功',
          //   skin: 'msg',
          //   time: 2 //2秒后自动关闭
          // });
          // location.reload();
          // console.log(file.name+'上传完成');
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
        layer.closeAll();
        layer.open({
          shade: true,
          content: '上传成功',
          skin: 'msg',
          time: 3 //2秒后自动关闭
        });
        location.reload();
        console.log('队列中的文件全部上传完成',queueData);
      }
  });
})

var images = {
  localId: [],
  serverId: []
};

var i = 0;

$(function() {
  $(".button-upload-back").on('click', function() {
    location.href="{{route('home')}}";
  });

  $(".button-select-img").on('touchstart', function() {
    wx.chooseImage({
      count: 4, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album'], // 可以指定来源是相册还是相机，默认二者都有
      success: function(res) {
        $(".button-upload-img").find('.text').text('开始上传(已选择' + res.localIds.length + ' 张图片)');
        //alert('已选择 ' + res.localIds.length + ' 张图片');
        images.localIds = res.localIds;
        var imghtml = '';
        for(var j=0; j<res.localIds.length; j++) {
          imghtml += '<img src="'+res.localIds[j]+'" width="100" height="100"/>';
        }
        $(".img-box").html(imghtml);
        if (res.localIds.length == 0) {
          alert('请先使用 chooseImage 接口选择图片');
          return;
        }
        $(".button-select-img").attr("disabled", "disabled");
        $(".button-upload-img").removeClass("hide");
      }
    });
  })

  //上传图片
  $(".button-upload-img").on('touchstart', function() {
    layer.open({  type: 2,content: '上传中' });
    upload();
  })
});

function upload(){
  // alert(images.localIds);
  var serverIds = [];
  wx.uploadImage({
    localId: images.localIds[i],
    // isShowProgressTips: 1,
    success: function(res) {
      i++;
      //alert('已上传：' + i + '/' + length);
      //alert("333"+JSON.stringify(res));
      //alert(res.serverId);
      images.serverId.push(res.serverId);
      if (i < images.localIds.length) {
        upload();
      } else {
        //alert("3323"+JSON.stringify(images.serverId));
        //alert(JSON.stringify(images.serverId));
        if (images.serverId) {
          //将图片下载到服务器
          $.ajax({
            type:'get',
            url: "{{route('uploadimg')}}",
            data: {'media_ids':images.serverId.join(",")},
            dataType: 'json',
            success: function(res) {
              layer.closeAll();
              $(".button-select-img").attr("disabled", "");
              $(".button-upload-img").addClass("hide").find('.text').text('开始上传');
              // alert(res);
              layer.open({
                shade: true,
                content: '上传成功',
                skin: 'msg',
                time: 2 //2秒后自动关闭
              });
            },
      			error: function(err) {
              alert('上传失败');
      				// alert(JSON.stringify(err));
      			}
          })
        }
      }
    }
  });
}
</script>
@endsection
