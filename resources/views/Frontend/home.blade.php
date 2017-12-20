@extends('Frontend.layouts.default')
@section('title')
  乐趴创意礼物
@endsection
@section('css')
  <style>
    .slogin a {color: #615d5d; text-decoration: none;}
    .slogin a span {font-size: 12px;}
  </style>
@endsection
@section('content')
<div class="box">
  <div class="item">
    <div class="box-item fl">
      <a href="{{route('showuploadimg')}}">
      <div class="box-item-logo">
        <img src="/img/1.png"/>
      </div>
      <div class="button">
        <div class="slogin">
          上传图片
          @if ($box->image)
            <span>(已上传)</span>
          @endif

        </div>
      </div>
      </a>
    </div>
    <div class="box-item fr">
      <a href="{{route('showuploadvideo')}}">
      <div class="box-item-logo">
        <img src="/img/2.png"/>
      </div>
      <div class="button">
        <div class="slogin">
            上传视频
            @if ($box->video)
              <span>(已上传)</span>
            @endif
        </div>
      </div>
      </a>
    </div>
    <div class="clear"></div>
  </div>
  <div class="item">
    <div class="box-item fl">
      <a href="{{route('showuploadvoice')}}">
      <div class="box-item-logo">
        <img src="/img/3.png"/>
      </div>
      <div class="button">
        <div class="slogin">
            录制语音
            @if ($box->voice)
              <span>(已上传)</span>
            @endif
        </div>
      </div>
      </a>
    </div>
    <div class="box-item fr">
      <a href="{{route('showuploadtext')}}">
      <div class="box-item-logo">
        <img src="/img/4.png"/>
      </div>
      <div class="button">
        <div class="slogin">

            输入文字
            @if ($box->body)
              <span>(已输入)</span>
            @endif

        </div>
      </div>
      </a>
    </div>
    <div class="clear"></div>
  </div>
</div>
@endsection
