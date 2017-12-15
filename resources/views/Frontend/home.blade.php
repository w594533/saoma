@extends('Frontend.layouts.default')
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
      <div class="box-item-logo">
        <img src="/img/1.png"/>
      </div>
      <div class="button">
        <div class="slogin">
          <a href="{{route('showuploadimg')}}">
          上传图片
          @if ($box->image)
            <span>(已上传)</span>
          @endif
          </a>
      </div>
      </div>
    </div>
    <div class="box-item fr">
      <div class="box-item-logo">
        <img src="/img/2.png"/>
      </div>
      <div class="button">
        <div class="slogin">
          <a href="{{route('showuploadvideo')}}">
            上传视频
            @if ($box->video)
              <span>(已上传)</span>
            @endif
          </a>
        </div>
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
        <div class="slogin">
          <a href="{{route('showuploadvoice')}}">
            录制语音
            @if ($box->voice)
              <span>(已上传)</span>
            @endif
          </a>
        </div>
      </div>
    </div>
    <div class="box-item fr">
      <div class="box-item-logo">
        <img src="/img/4.png"/>
      </div>
      <div class="button">
        <div class="slogin">
          <a href="{{route('showuploadtext')}}">
            输入文字
            @if ($box->body)
              <span>(已输入)</span>
            @endif
          </a>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
@endsection
