<!DOCTYPE html>
<html>
 <head>
  <title>End of part 1 page</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
  <style type="text/css">
   .box{
    width:600px;
    margin:0 auto;
    border:1px solid #ccc;
    height:250px;
   }
   .next-button{
    float:right;
     padding-right:10%;
   }
  </style>
 </head>
 <body>
  <br />
  <div class="container box">
   <h3 align="center">TIME IS UP</h3><br />
    <div class="alert alert-danger success-block">
     <strong>じかんになりました。</strong>
     <br />
    </div>
   
   <br />
   <div class = "next-button">
   <button type="button" onclick="window.location='{{ url('/Q5ReadingWelcome')}}'" class="middle-screen">つぎの　もんだいへ</button>
    <div>
    
  </div>
 </body>
</html>
