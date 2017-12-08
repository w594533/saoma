@extends('Frontend.layouts.default')

@section('content')
<div class="box">
  <div class="item">
    <div class="box-item fl">
      <div class="box-item-logo">
        <img src="/img/1.png"/>
      </div>
      <div class="button">
        <div class="slogin"><a href="{{route('showuploadimg')}}">上传图片</a></div>
      </div>
    </div>
    <div class="box-item fr">
      <div class="box-item-logo">
        <img src="/img/2.png"/>
      </div>
      <div class="button">
        <div class="slogin"><a href="{{route('showuploadvideo')}}">上传视频</a></div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="item">
    <div class="box-item fl">
      <div class="box-item-logo">
        <img src="/img/3.png"/>
      </div>
      <div class="button">
        <div class="slogin"><a href="{{route('showuploadvoice')}}">录制语音</a></div>
      </div>
    </div>
    <div class="box-item fr">
      <div class="box-item-logo">
        <img src="/img/4.png"/>
      </div>
      <div class="button">
        <div class="slogin"><a href="{{route('showuploadtext')}}">输入文字</a></div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
@endsection
