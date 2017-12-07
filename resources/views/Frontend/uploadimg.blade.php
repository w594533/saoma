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

$(function() {
  $(".button-upload-img").click(function() {
    wx.chooseImage({
      count: 4, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album'], // 可以指定来源是相册还是相机，默认二者都有
      success: function(res) {
        images.localId = res.localIds;
        alert('已选择 ' + res.localIds.length + ' 张图片');
        if (images.localId.length == 0) {
          alert('请先使用 chooseImage 接口选择图片');
          return;
        }
        upload(images.localId);
      }
    });
  })
});

  function upload(localIds){
    var serverIds = [];
    var imghtml = '';
    for(var i=0; i<localIds.length; i++)  {
      wx.uploadImage({
        localId: localIds[i],
        success: function(res) {
          alert('已上传：' + i + '/' + length);
          serverIds.push(res.serverId);
          imghtml +='<img src="'+localIds[i]+'" width="100" height="100"/>';
        },
        fail: function(res) {
          alert(JSON.stringify(res));
        }
      });
    }
    $(".img-box").html(imghtml);

    alert(JSON.stringify(serverIds));
    //将图片下载到服务器
    $.ajax({
      type:'post',
      url: '{{route("uploadimg")}}',
      data: {'media_ids[]':serverIds},
      success: function(res) {
        alert(res);
        alert('上传成功');
      }
    })
  }

</script>
@endsection
