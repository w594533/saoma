@extends('Frontend.layouts.default')

@section('content')
<div class="img-box">
  {{-- <img src="/img/1.png"/>
  <img src="/img/1.png"/>
  <img src="/img/1.png"/> --}}
</div>
<div class="upload-btn">
  <a href="javascript:void(0)" class="button-upload-voice">开始录制</a>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
{!! $jssdk !!}

<script>

$(".button-upload-voice").click(function() {
  wx.startRecord();
})

</script>
@endsection
