@extends('Frontend.layouts.default')

@section('content')
<div class="img-box">
  <img src="/img/1.png"/>
  <img src="/img/1.png"/>
  <img src="/img/1.png"/>
</div>
<div class="upload-btn">
  <a href="javascript:void(0)" class="button-upload-img">上传图片</a>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
{!! $jssdk !!}

<script>
$(function() {
  $(".button-upload-img").click(function() {
    var images = {
      localId: [],
      serverId: []
    };
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
        var i = 0, length = images.localId.length;
        images.serverId = [];
        function upload() {
          wx.uploadImage({
            localId: images.localId[i],
            success: function(res) {
              i++;
              alert('已上传：' + i + '/' + length);
              images.serverId.push(res.serverId);
              if (i < length) {
                upload();
              } else {
                alert(images.serverId);
                var imghtml = '';
                for(var i=0; i<images.localId.length; i++) {
                  imghtml +='<img src="'+localIds[i]+'"/>';
                }
                $(".img-box").html(imghtml);
              }
            },
            fail: function(res) {
              alert(JSON.stringify(res));
            }
          });
        }
        upload();
      }
    });

    // wx.chooseImage({
    //   count: 4, // 默认9
    //   sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
    //   sourceType: ['album'], // 可以指定来源是相册还是相机，默认二者都有
    //   success: function (res) {
    //       var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
    //       alert(localIds);
    //       // 上传照片
    //       wx.uploadImage({
    //           localId: '' + localIds,
    //           isShowProgressTips: 1,
    //           success: function(res) {
    //               serverId = res.serverId;
    //               alert(serverId);
    //               alert(1);
    //               //$(obj).next().val(serverId); // 把上传成功后获取的值附上
    //           }
    //       });
    //   }
    // });
  })
})
</script>
@endsection
