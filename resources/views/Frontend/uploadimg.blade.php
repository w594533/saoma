@extends('Frontend.layouts.default')
@section('css')
  <style>
  .upload-btn .hide {display: none;}
  .upload-btn button {display: block; min-width: 120px; margin: 10px auto;}
  </style>
@endsection
@section('content')
<div class="img-box">
  @foreach ($images as $key => $image)
    <img src="{{Storage::url($image)}}" width="100" height="100"/>
  @endforeach
</div>
<div class="upload-btn">
  <button class="button-select-img btn btn-default btn-sm"><i class="fa fa-save"></i> <span class="text">选择图片</span></button>
  <button class="button-upload-img btn btn-default btn-sm"><i class="fa fa-cloud-upload"></i> <span class="text">开始上传</span></button>
  <button class="button-upload-back btn btn-default btn-sm"><i class="fa fa-mail-reply"></i> <span class="text">返回</span></button>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
{!! $jssdk !!}

<script>

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
  alert(images.localIds);
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
              alert(res);
              layer.open({
                shade: true,
                content: '上传成功',
                skin: 'msg',
                time: 2 //2秒后自动关闭
              });
            },
      			error: function(err) {
      				alert(JSON.stringify(err));
      			}
          })
        }
      }
    }
  });
}
</script>
@endsection
