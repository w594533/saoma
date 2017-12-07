@extends('Frontend.layouts.default')

@section('content')
<div class="img-box">
</div>
<a href="javascript:void(0)" class="button-upload-img">上传图片</a>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
{!! $jssdk !!}

<script>
$(function() {
  $(".button-upload-img").click(function() {
    wx.chooseImage({
      count: 4, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
          var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
          alert(localIds);
          // 上传照片
          wx.uploadImage({
              localId: '' + localIds,
              isShowProgressTips: 1,
              success: function(res) {
                  serverId = res.serverId;
                  alert.log(serverId);
                  //$(obj).next().val(serverId); // 把上传成功后获取的值附上
              }
          });
      }
    });
  })
})
</script>
@endsection
