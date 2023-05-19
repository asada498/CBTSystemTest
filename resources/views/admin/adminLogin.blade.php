<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>管理者ログイン</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

 </head>
 <body>
  <br />
  <div class="container">
   <div class = "title-login">
   <h3 align="center">管理者ログイン</h3>
   <br />
    </div>
   @if ($message = Session::get('error'))
   <div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
   </div>
   @endif

   @if (count($errors) > 0)
    <div class="alert alert-danger">
     <ul>
     @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
     @endforeach
     </ul>
    </div>
   @endif

   <form method="post" action="{{ url('/admin/checklogin') }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class = "left-side-login">
            <label class = "normal-label">ＩＤ</label>
        </div>
        <div class = "right-side-login">     
            <input type="text" name="id" class="form-control" required maxlength="50"/>
        </div>
    </div>
    <div class="form-group">
        <div class = "left-side-login">
            <label class = "normal-label">パスワード</label>
        </div>
        <div class = "right-side-login">     
            <input type="password" name="password" class="form-control" required maxlength="50"/>
        </div>
    </div>
    <div class="form-group login-button">
     <input type="submit" name="login" class="btn btn-primary" value="Login" />
    </div>
   </form>
  </div>
 </body>
</html>