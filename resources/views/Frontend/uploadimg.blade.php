@extends('Frontend.layouts.default')

@section('content')
<div class="img-box">
  {{-- <img src="/img/1.png"/>
  <img src="/img/1.png"/>
  <img src="/img/1.png"/> --}}
</div>
<div class="upload-btn">
  <a href="javascript:void(0)" class="button-upload-img">上传图片</a>
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
  $(".button-upload-img").click(function() {
    wx.chooseImage({
      count: 4, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album'], // 可以指定来源是相册还是相机，默认二者都有
      success: function(res) {
        //alert('已选择 ' + res.localIds.length + ' 张图片');
        images.localIds = res.localIds;
        if (res.localIds.length == 0) {
          alert('请先使用 chooseImage 接口选择图片');
          return;
        }
        upload();
      }
    });
  })
});

function upload(){
  alert(images.localIds);
  var serverIds = [];
  var imghtml = '';
  wx.uploadImage({
    localId: images.localIds[i],
    isShowProgressTips: 1,
    success: function(res) {
      i++;
      //alert('已上传：' + i + '/' + length);
      //alert("333"+JSON.stringify(res));
      //alert(res.serverId);
      imghtml += '<img src="'+images.localIds[i]+'" width="100" height="100"/>';
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
              $(".img-box").html(imghtml);
              alert(res);
              alert('上传成功');
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
