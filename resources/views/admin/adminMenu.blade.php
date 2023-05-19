<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>管理者メニュー</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">

 </head>
 <body>
   <h3 align="center">管理者メニュー</h3>

   <form method="post" action="{{ url('/admin/menuPush') }}">
    {{ csrf_field() }}
        <br>
        <h3 align="center">
            <button type="submit" name="mode" value="edit" style="width: 25%;">入金、受験者情報検索</button><br><br>
            <button type="submit" name="mode" value="password" style="width: 25%;">管理者パスワード変更</button><br><br>
            <button type="submit" name="mode" value="sitepassword" style="width: 25%;">会場管理者パスワード管理</button>
        </h3>
   </form>
 </body>
</html>