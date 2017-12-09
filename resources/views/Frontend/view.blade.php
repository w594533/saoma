@extends('Frontend.layouts.default')
@section('css')
  <link rel="stylesheet" href="/css/swiper.min.css">
  <link rel="stylesheet" href="/css/plyr/plyr.css">
  <link rel="stylesheet" href="/css/plyr/demo.css">
  {{-- <link rel="preload" as="font" crossorigin type="font/woff2" href="https://cdn.plyr.io/static/fonts/avenir-medium.woff2">
  <link rel="preload" as="font" crossorigin type="font/woff2" href="https://cdn.plyr.io/static/fonts/avenir-bold.woff2"> --}}
  <link rel="stylesheet" href="/css/view.css">
@endsection
@section('js')
  <script src="/js/swiper.min.js"></script>
@endsection
@section('content')
<div class="view-box">
  @if ($box->body)
    <div class="text-box item">
      <div class="solign"><img src="/img/4.png"/></div>
      <div class="inner">
        {{$box->body}}
      </div>
    </div>
  @endif

  @if ($box->voice)
    <div class="voice-box item">
      <div class="solign" style="margin-bottom: 0;"><img src="/img/3.png"/></div>
      <div class="inner">
        <audio src="{{Storage::url($box->voice)}}" preload="auto" controls></audio>
      </div>
    </div>
  @endif

  @if ($box->img_path)
    <div class="image-box item">
      <div class="solign"><img src="/img/1.png"/></div>
      <div class="inner">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            @foreach ($box->img_path as $image)
              <div class="swiper-slide"><img src="{{ Storage::url($image) }}"/></div>
            @endforeach
          </div>
          <!-- 如果需要分页器 -->
          {{-- <div class="swiper-pagination"></div> --}}

          <!-- 如果需要导航按钮 -->
          {{-- <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>

          <!-- 如果需要滚动条 -->
          <div class="swiper-scrollbar"></div> --}}
        </div>

      </div>
    </div>
  @endif

  @if ($box->video)
    <div class="video-box item">
      <div class="solign"><img src="/img/2.png"/></div>
      <div class="inner">
        <video controls>
          <source src="{{ Storage::url($box->video) }}" type="video/mp4">
        </video>
      </div>
    </div>
  @endif
</div>
<script>
    var swiper = new Swiper('.swiper-container', {
        autoHeight: true, //高度随内容变化
    });
</script>
<script src="/js/plyr/plyr.js"></script>
<script src="/js/plyr/demo.js"></script>
{{-- <script src="https://cdn.rangetouch.com/1.0.1/rangetouch.js" async></script>
<script src="https://cdn.shr.one/1.0.1/shr.js"></script> --}}
<script>
    // if (window.shr) { window.shr.setup({ count: { classname: 'btn__count' } }); }
</script>
@endsection
