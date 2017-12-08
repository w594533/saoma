@extends('Frontend.layouts.default')
@section('css')
  <style>
    .content {
      width: auto;
      padding: 20px;
    }
    #body {padding: 10px 0; border: 1px solid #ddd; resize: none; border-radius: 4px; min-height: 200px; width: 100%;}
  </style>
@endsection
@section('content')
  <div class="text-box">
    {{ csrf_field() }}
    <textarea name="body" id="body">{{$box->body}}</textarea>
  </div>
  <div class="upload-btn">
    <button class="save btn btn-default btn-sm"><i class="fa fa-save"></i> 保存</button>
    <button class="button-upload-back btn btn-default btn-sm"><i class="fa fa-mail-reply"></i> 返回</button>
  </div>
<script>
$(".save").on('touchstart', function() {
  var body = $("#body").val();
  if (body) {
    layer.open({  type: 2,content: '保存中' });
    $.ajax({
      type:'post',
      url: "{{route('uploadtext')}}",
      data: {'body': body, '_token': $('input[name="_token"]').val()},
      dataType: 'json',
      success: function(res, statusCode) {
        layer.closeAll();
        layer.open({
          content: '保存成功'
          ,skin: 'msg'
          ,time: 2 //2秒后自动关闭
        });
        alert(statusCode);
      },
      error: function(err) {
        alert('保存失败');
      }
    })
  }
})

$(".button-upload-back").on('click', function() {
  location.href="{{route('home')}}";
});
</script>
@endsection
