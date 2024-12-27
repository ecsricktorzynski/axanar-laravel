<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Axanar Donors | Axanar Fundraiser Perk Fulfillment</title>
    <meta name="description" content="Axanar Donors - Axanar Fundraiser Perk Fulfillment">
    <meta name="author" content="Axanar Donors">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <link rel="shortcut icon" href="/images/client/favicon.png">
    <link rel="apple-touch-icon" href="/images/apple-icon/icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="/images/apple-icon/icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="/images/apple-icon/icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="/images/apple-icon/icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="/images/apple-icon/icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="/images/apple-icon/icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="/images/apple-icon/icon152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="/images/apple-icon/icon180.png" sizes="180x180">
    <link href="https://fonts.googleapis.com/css?family=Lato|Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="/css/plugins.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/themes.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/custom.css">
    <script>
      function clearAlert(alertToClear) {
        $.get('clearAlert.php', {alert: alertToClear});
      }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="/js/vendor/modernizr-respond.min.js"></script>
    @stack('head-scripts')
</head>
<body style="background-color: #000000;">
<div id="page-container">
    @include('layouts.index-navbar')
    @yield('content')
</div>
<a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>
@stack('body-scripts')
</body>
@stack('html-scripts')
</html>