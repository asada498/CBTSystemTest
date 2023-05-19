<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Change Password</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script>
    function check(){
        p1 = window.document.myForm.password1.value;
        if(p1 == null || p1 == ""){
            alert("Enter password");
            return;
        }
        p2 = window.document.myForm.password2.value;
        if(p2 == null || p2 == ""){
            alert("Enter confirmation password");
            return;
        }
        if(p1 != p2){
            alert("Password and confirmation password do not match");
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
   <h3 align="center">Password Changing</h3>
   <br/>
  </div>

   <form method="post" name="myForm" action="{{ url('/admin/supervisorPasswordRegist') }}">
   {{ csrf_field() }}
     <h3>
        <label>New Password</label>
        <input type="password" name="password1" style="margin-left: 50px;" required maxlength="50"/>
        <br>
        <label>Confirm</label>
        <input type="password" name="password2" style="margin-left: 126px;" required maxlength="50"/>
        <br><br>        
        <input type="button" value="CANCEL" onclick="history.back();" style="width: 15%;"/>
        <input type="reset" value="CLEAR" style="width: 15%; margin-left: 50px;"/>
        <input type="button" value="OK" onclick="check();" style="width: 15%;  margin-left: 50px;"/>
     </h3>
   </form>
  </div>
 </body>
</html>