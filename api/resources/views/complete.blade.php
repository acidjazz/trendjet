<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <title>trendjet API</title>
    <script>
      window.opener.postMessage({!! $json !!}, '*');
      window.close();
    </script>
  </head>
  <body>Processing Login</body>
</html>
