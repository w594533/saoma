@extends('Frontend.layouts.default')
@section('css')
  <style>
  .note {text-align: center; color: red; margin: 10px 0; font-size: 12px;}
  .upload-btn button {display: block; min-width: 120px; margin: 10px auto;}
  .upload-btn .hide {display: none;}
  </style>
@endsection
@section('content')
{{-- <div class="img-box"> --}}
  {{-- <img src="/img/1.png"/>
  <img src="/img/1.png"/>
  <img src="/img/1.png"/> --}}
{{-- </div> --}}
<div class="upload-btn">
  <button class="button-upload-voice btn btn-default btn-sm hide"><i class="fa fa-cloud-upload"></i> 上传语音</button>
  <button class="button-start-voice btn btn-default btn-sm"><i class="fa fa-file-audio-o"></i> 开始录制</button>
  <button class="button-stop-voice btn btn-default btn-sm hide"><i class="fa fa-stop"></i> 停止录制</button>
  <button class="button-play-voice btn btn-default btn-sm hide"><i class="fa fa-play-circle-o"></i> 播放</button>
  <button class="button-pause-voice btn btn-default btn-sm hide"><i class="fa fa-pause-circle-o"></i> 暂停</button>
  <button class="button-upload-back btn btn-default btn-sm"><i class="fa fa-mail-reply"></i> 返回</button>
</div>
<div class="note">
    <span class="red">*最多录制一分钟语音，超过一分钟会自动停止</span>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
{!! $jssdk !!}

<script>
var recordTimer;
var voice = {
  localId: ''
};
//按下开始录音
$('.button-start-voice').on('touchstart', function(event){
    event.preventDefault();
    START = new Date().getTime();
    recordTimer = setTimeout(function(){
      $(".button-upload-voice").addClass("hide");
      $(".button-play-voice").addClass("hide");
      $(".button-pause-voice").addClass("hide");
      $(".button-stop-voice").removeClass("hide");
      $(".button-start-voice").attr("disabled", "disabled").text('正在录音...');
        wx.startRecord({
            success: function(){
                localStorage.rainAllowRecord = 'true';
            },
            cancel: function () {
                alert('用户拒绝授权录音');
            }
        });
    },300);
});

//结束录音
$('.button-stop-voice').on('touchstart', function(event){
    alert('123');
    event.preventDefault();
    END = new Date().getTime();

    if((END - START) < 300){
      alert('456');
        END = 0;
        START = 0;
        //小于300ms，不录音
        clearTimeout(recordTimer);
    }else{
      alert('789');
      $(".button-upload-voice").removeClass("hide");
      $(".button-play-voice").removeClass("hide");
      $(".button-pause-voice").removeClass("hide");
      $(".button-stop-voice").addClass("hide");
        $(".button-start-voice").attr("disabled", "").text('重新录制');
        wx.stopRecord({
          success: function (res) {
            voice.localId = res.localId;
            // uploadVoice();
          },
          fail: function (res) {
            alert(JSON.stringify(res));
          }
        });
    }
});

wx.onVoiceRecordEnd({
    // 录音时间超过一分钟没有停止的时候会执行 complete 回调
    complete: function (res) {
        $(".button-upload-voice").removeClass("hide");
        $(".button-play-voice").removeClass("hide");
        $(".button-pause-voice").removeClass("hide");
        $(".button-stop-voice").addClass("hide");
        $(".button-start-voice").attr("disabled", "").text('重新录制');
        voice.localId = res.localId;
    }
});

//播放
$(".button-play-voice").on('touchstart', function() {
  alert(JSON.stringify(voice));
  $(".button-stop-voice").addClass("hide");
  $(".button-start-voice").attr("disabled", "").text('重新录制');
  $(".button-pause-voice").removeClass("hide");
  wx.playVoice({
    localId: voice.localId // 需要暂停的音频的本地ID，由stopRecord接口获得
  });
})

//上传
$(".button-upload-voice").on('touchstart', function() {
  //调用微信的上传录音接口把本地录音先上传到微信的服务器
    //不过，微信只保留3天，而我们需要长期保存，我们需要把资源从微信服务器下载到自己的服务器
    layer.open({  type: 2,content: '上传中' });
    $(".button-upload-voice").attr("disabled", "disabled").text('上传中...');
    wx.uploadVoice({
        localId: voice.localId, // 需要上传的音频的本地ID，由stopRecord接口获得
        // isShowProgressTips: 1, // 默认为1，显示进度提示
        success: function (res) {
            //把录音在微信服务器上的id（res.serverId）发送到自己的服务器供下载。
            $.ajax({
              type:'get',
              url: "{{route('uploadvoice')}}",
              data: {'media_id':res.serverId},
              dataType: 'json',
              success: function(res) {
                layer.closeAll();
                layer.open({
                  shade: true,
                  content: '上传成功',
                  skin: 'msg',
                  time: 2 //2秒后自动关闭
                });
                $(".button-upload-voice").attr("disabled", "").addClass("hide").text('上传语音');
                alert(res);
              },
        			error: function(err) {
                alert('上传失败');
        				alert(JSON.stringify(err));
        			}
            })
        }
    });
})

$(".button-upload-back").on('click', function() {
  location.href="{{route('home')}}";
});
</script>
@endsection
