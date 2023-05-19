<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>管理者パスワード変更</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script>
    function check(){
        p1 = window.document.myForm.password1.value;
        if(p1 == null || p1 == ""){
            alert("パスワードが入力されていません");
            return;
        }
        p2 = window.document.myForm.password2.value;
        if(p2 == null || p2 == ""){
            alert("確認用パスワードが入力されていません");
            return;
        }
        if(p1 != p2){
            alert("パスワードと確認用パスワードが一致しません");
            return;
        }
        window.document.myForm.submit();
    }
  </script>
 </head>
 <body>
  <br />
  <div class="container">
   <div class = "title-login">
   <h3 align="center">管理者パスワード変更</h3>
   <br/>
  </div>

   <form method="post" name="myForm" action="{{ url('/admin/passwordRegist') }}">
   {{ csrf_field() }}
     <h3>
        <label>新規パスワード</label>
        <input type="password" name="password1" style="margin-left: 110px;" required maxlength="50"/>
        <br>
        <label>新規パスワード(確認用)</label>
        <input type="password" name="password2" style="margin-left: 22px;" required maxlength="50"/>
        <br><br>        
        <input type="button" value="キャンセル" onclick="history.back();" style="width: 15%;"/>
        <input type="reset" value="クリア" style="width: 15%; margin-left: 50px;"/>
        <input type="button" value="OK" onclick="check();" style="width: 15%;  margin-left: 50px;"/>
     </h3>
   </form>
  </div>
 </body>
</html>