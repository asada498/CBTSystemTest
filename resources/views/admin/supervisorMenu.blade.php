<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Examiner Menu</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
    <br>
    <h3 align="center">Menu</h3>
    <form method="post" action="{{ url('/admin/supervisorMenuPush') }}">
    {{ csrf_field() }}
        <br>
        <h3 align="center">
            <button type="submit" name="mode" value="progress" style="width: 25%;">Exam Progress</button><br><br>
            <button type="submit" name="mode" value="password" style="width: 25%;">Change Your Password</button><br><br>
        </h3>
    </form>
</body>
</html>