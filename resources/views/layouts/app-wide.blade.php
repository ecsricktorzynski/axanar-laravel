<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <title>{{ config('app.versionName') }} | @yield('mediaTitle', 'Axanar Fundraiser Perk Fulfillment')</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no"/>
    <meta name="description"
        content="{{ config('app.versionName') }} | @yield('mediaTitle', 'Axanar Fundraiser Perk Fulfillment')">
    <meta name="robots" content="noindex, nofollow">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/apple-icon/icon76.png">
    <link rel="icon" type="image/png" href="/images/client/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Lato|Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="/css/plugins.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/themes.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/custom.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="/js/vendor/modernizr-respond.min.js"></script>
    <script>
      function clearAlert(alertToClear) {
        $.get('clearAlert.php', {alert: alertToClear});
      }
    </script>
    @stack('head-scripts')
</head>
<body style="background-color: #000000;">
@include('layouts.app-navbar')
<section class="site-content site-section">
    <div class="container">
        @yield('content')
    </div>
</section>
@include('layouts.page-footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="/js/plugins.js"></script>
<script src="/js/app.js"></script>
<script type="application/javascript">
  (function(i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function() {
      (i[r].q = i[r].q || []).push(arguments);
    }, i[r].l = 1 * new Date();
    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m);
  })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
  ga('create', 'UA-2146868-46', 'auto');
  ga('send', 'pageview');
</script>
<script type="text/JavaScript">
  (function($) {
    $('[data-toggle="tooltip"]').tooltip();

    window.setTimeout(function() {
      location.href = '/logout';
    }, 3540000);
  })(jQuery);
</script>
@stack('body-scripts')
</body>
@stack('html-scripts')
</html>
