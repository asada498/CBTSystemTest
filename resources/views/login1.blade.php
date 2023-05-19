<!DOCTYPE html>
<html>
 <head>
  <title>Simple Login Page</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script>
    // keyboard disable
    $(function(){
      $(document).keydown(function(event){
        var keyCode = event.keyCode;    // keycode
        var ctrlClick = event.ctrlKey;  // Ctrl(true or false)
        var altClick = event.altKey;    // Alt(true or false)
        var obj = event.target;         // object
        
        switch(keyCode){
        case 122:   // F11
        case 37:    // ←
        case 39:    // →
        case 8:     // BS
        case 46:    // DEL
        case 36:    // HOME
        case 35:    // END
        case 9:     // TAB
            return true;
        }
        if(48 <= keyCode && keyCode <= 57){ // number
            return true;
        }
        if(96 <= keyCode && keyCode <= 105){ // number
            return true;
        }
        // other key disable
        return false;

      });
    });
  </script>
  <!-- <style type="text/css">
   .box{
    width:600px;
    margin:0 auto;
    border:1px solid #ccc;
   }
  </style> -->
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

 </head>
 <body>
  <br />
  <div class="container">
   <div class = "title-login">
   <h3 align="center">にほんご　NAT-TEST　１Q　へ　ようこそ</h3>
   <h3 align="center">Welcome to Japanese NAT-TEST １Q</h3><br />
    </div>
   @if(isset(Auth::user()->email))
    <script>window.location="/main/successlogin";</script>
   @endif

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

   <form method="post" action="{{ url('/main/checklogin') }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class = "left-side-login">
            <label class = "normal-label">じゅけんばんごうを　いれてください<br />Put your testee's number.</label>
        </div>
        <div class = "right-side-login">     
            <input type="text" name="examineeId" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <div class = "left-side-login">
            <label class = "normal-label">あんしょうを　いれてください<br />Put your pin.</label>
        </div>
        <div class = "right-side-login">     
            <input type="text" name="examineeNumber" class="form-control" />
        </div>
    </div>
    <div class="form-group login-button">
     <input type="submit" name="login" class="btn btn-primary" value="Login" />
    </div>
   </form>
  </div>
 </body>
</html>