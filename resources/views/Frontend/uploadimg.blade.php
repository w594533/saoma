@extends('Frontend.layouts.default')
@section('css')
  <style>
  .hide {display: none !important;}
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
  {{ csrf_field() }}
  <button class="button-select-img btn btn-default btn-sm"><i class="fa fa-save"></i> <span class="text">选择图片</span></button>
  <button class="button-upload-img btn btn-default btn-sm hide"><i class="fa fa-cloud-upload"></i> <span class="text">开始上传</span></button>
  <button class="button-upload-back btn btn-default btn-sm"><i class="fa fa-mail-reply"></i> <span class="text">返回</span></button>
</div>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
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
        $(".button-upload-img").removeClass("hide").find('.text').text('开始上传(已选择' + res.localIds.length + ' 张图片)');
        //alert('已选择 ' + res.localIds.length + ' 张图片');
        images.localIds = res.localIds;
        var imghtml = '';
        //alert(window.wxjs_is_wkwebview);
        if (window.wxjs_is_wkwebview) {
          //此接口仅在 iOS WKWebview 下提供
          for(var j=0; j<res.localIds.length; j++) {
            wx.getLocalImgData({
              localId: res.localIds[j], // 图片的localID
              success: function (res) {
                var localData = res.localData; // localData是图片的base64数据，可以用img标签显示
                imghtml += '<img src="'+localData+'" width="100" height="100"/>';
              }
            });
          }
        } else {
          for(var j=0; j<res.localIds.length; j++) {
            imghtml += '<img src="'+res.localIds[j]+'" width="100" height="100"/>';
          }
        }
        $(".img-box").html(imghtml);
        if (res.localIds.length == 0) {
          alert('请先使用 chooseImage 接口选择图片');
          return;
        }

        $(".button-upload-img").removeClass("hide");
      }
    });
  })

  //上传图片
  $(".button-upload-img").on('touchstart', function() {
    // layer.open({  type: 2,content: '上传中' });
    // $(".button-select-img").attr("disabled", "disabled");
    // $(".button-upload-img").attr("disabled", "disabled");
    $(".button-select-img").addClass("hide").attr("disabled", "disabled");
    $(".button-select-img").addClass("hide");
    upload();
  })
});

function upload(){
  //alert(images.localIds);
  var serverIds = [];
  wx.uploadImage({
    localId: images.localIds[i],
    isShowProgressTips: 1,
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
          layer.open({  type: 2,content: '上传中' });
          //将图片下载到服务器
          $.ajax({
            type:'post',
            url: "{{route('uploadimg')}}",
            data: {'media_ids':images.serverId, _token:$('input[name="_token"]').val()},
            dataType: 'json',
            success: function(res) {
              if (res.status == 'ok') {
                layer.closeAll();
                $(".button-select-img").removeClass("hide").attr("disabled", "");
                $(".button-upload-img").addClass("hide").find('.text').text('开始上传');
                //alert(res);
                layer.open({
                  shade: true,
                  content: '上传成功',
                  skin: 'msg',
                  time: 3 //2秒后自动关闭
                });
                location.reload();
              }

            },
      			error: function(err) {
              layer.open({
    content: '由于文件较大，上传超时，请在网络良好的情况下上传'
    ,btn: ['确定']
    ,yes: function(index){
      alert(JSON.stringify(err));
      location.reload();
      // layer.close(index);
    }
  });

      			}
          })
        }
      }
    }
  });
}
</script>
@endsection
