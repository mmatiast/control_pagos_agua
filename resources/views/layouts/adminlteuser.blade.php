<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/login.css">
  <link rel="icon" type="image/png" href="/favicon.png">
</head>
<body class="login-page">
  <div class="login-box">
    @yield('content')
  </div>
</body>
</html>
