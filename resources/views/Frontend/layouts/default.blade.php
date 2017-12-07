<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
      <link rel="icon" href="favicon.ico" type="image/x-icon">
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
      <script src="/js/jquery-3.2.1.min.js"></script>
      <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
      <link rel="stylesheet" href="/css/normalize.css">
      <link rel="stylesheet" href="/css/main.css">
      @yield('js')
      <title>@yield('title')</title>
</head>
<body class="">
  <div class="container">
    <div class="content">
      @yield('content')
    </div>
  </div>
</body>
</html>
