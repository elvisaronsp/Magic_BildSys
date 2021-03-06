<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_TITLE') }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('css/google-web-fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
         body{
            background-image: url("{{ asset('img/backLogin.jpg') }}") !important;
            background-position: right bottom !important;
            background-repeat: no-repeat !important;
            background-size: contain !important;
        }
        .login-page, .register-page {
            background: #ddd9d8;
        }

    </style>
</head>
    @yield('body')
</html>
