@extends('Frontend.layouts.default')
@section('css')
  <style>
  .upload-btn .hide {display: none;}
  .upload-btn button {display: block; min-width: 120px; margin: 10px auto;}

  .z_file {width: 100px;height: 100px;background: url('/img/z_add.png') no-repeat;background-size: 100% 100%;float: left;margin-right: 0.2rem;}
  .z_file input::-webkit-file-upload-button {width: 1rem;height: 1rem;border: none;position: absolute;outline: 0;opacity: 0;}
  .z_file input#cert1 {display: block;width: auto;border: 0;vertical-align: middle; width: 100%; height: 100%; opacity: 0;}

  .upload-img-box {display: flex; justify-content: flex-start; flex-wrap: wrap;}

  .img-box {justify-content: flex-start; padding: 0; width: 100%;}
  .img-box .item {display: block; margin-right: 20px; position: relative;}
  .img-box .item .delete{position: absolute;width: 18px;height: 18px;background: url('/img/delete.png') no-repeat left top;background-size: cover;-webkit-background-size: cover;-moz-background-size: cover;display: block;right: -9px;top: -9px;}
  .z_file.hide {display: none !important;}
  </style>
@endsection
@section('js')
  <script
  src="http://code.jquery.com/jquery-migrate-3.0.1.min.js"
  integrity="sha256-F0O1TmEa4I8N24nY0bya59eP6svWcshqX1uzwaWC4F4="
  crossorigin="anonymous"></script>
  <script type="text/javascript" src="/js/UploadPic.js"></script>
@endsection
@section('content')
  <div class="upload-img-box">
    <div class="img-box">
      @foreach ($images as $key => $image)
        <div class="item"><img src="{{Storage::url($image)}}" width="100" height="100"/></div>
      @endforeach
    </div>

      <div class="z_file @if (count($images) > 0) hide @endif">
        <input id="cert1" type="file" accept="image/*" multiple class="file_posi" />
      </div>

  </div>
<div class="upload-btn">
  {{ csrf_field() }}
  @if ($images)
    <button class="button-reupload-img btn btn-default btn-sm"><i class="fa fa-cloud-upload"></i> <span class="text">重新选择图片</span></button>
    <button class="button-upload-img btn btn-default btn-sm hide"><i class="fa fa-cloud-upload"></i> <span class="text">开始上传</span></button>
  @else
    <button class="button-upload-img btn btn-default btn-sm hide"><i class="fa fa-cloud-upload"></i> <span class="text">开始上传</span></button>
  @endif
  <button class="button-upload-back btn btn-default btn-sm"><i class="fa fa-mail-reply"></i> <span class="text">返回</span></button>
</div>

<script>
var imgs = [];
$(function() {
  upload("cert1");//上传证书1
  check_len();
  $(".delete").live('click', function() {
    $(this).parent().remove();
    check_len();
  })

  //重新上传图片
  $(".button-reupload-img").click(function() {
    layer.open({  type: 2,content: '请稍等' });
    $.ajax({
      type: 'post',
      url: "{{route('deleteuploadimg')}}",
      data: {_token:$('input[name="_token"]').val()},
      dataType: 'json',
      success: function(res) {
        if (res.status == 'ok') {
          layer.closeAll();
          $(".button-reupload-img").remove();
          $(".img-box").html('');
          $(".z_file").removeClass("hide");
        }
      }
    })
  });

  //开始上传图片
  $(".button-upload-img").click(function() {
    if (imgs.length <= 0) {
      alert('您未选择图片');
      return false;
    }
    if (imgs.length > 0) {
      layer.open({  type: 2,content: '上传中' });
      //将图片下载到服务器
      $.ajax({
        type:'post',
        url: "{{route('uploadimg')}}",
        data: {_token:$('input[name="_token"]').val(), 'imgs[]': imgs},
        dataType: 'json',
        success: function(res) {
          if (res.status = 'ok') {
            layer.closeAll();
            $(".button-upload-img").addClass("hide").find('.text').text('开始上传');
            layer.open({
              shade: true,
              content: '上传成功',
              skin: 'msg',
              time: 2 //2秒后自动关闭
            });
            location.reload();
          }
        },
  			error: function(err) {
          alert('上传失败');
  				// alert(JSON.stringify(err));
  			}
      })
    }
  });
})

/*
	 * id file input的id
	 * thumb 缩略图id
	 */
	function upload(id){
		//上传证书
		 var u = new UploadPic();
		 u.init({
			input: document.getElementById(id),
			callback: function (base64,fileType) {
        imgs.push(base64);
        var html = '<div class="item"><img src="'+base64+'" width="100" height="100" class="base"/><span class="delete"></span></div>';
        $(".img-box").append(html);
        check_len();
				// $("#"+thumb).attr("src",base64);
				// $("#"+thumb).attr("filetype",fileType);
			},
			loading: function () {
				//say_error("等待上传...");
			}
		});
	}

  function check_len() {
    if ($(".img-box").find("img.base").length >= 1) {
      $(".button-upload-img").removeClass("hide");
    }

     if ($(".img-box").find("img.base").length >= 4) {
       $(".z_file").hide();
     } else {
       $(".z_file").show();
     }
  }

  $(".button-upload-back").on('click', function() {
      location.href="{{route('home')}}";
  });
</script>
@endsection
