<!DOCTYPE html>
<html>
 <head>
  <title>Picture Page</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
  <style>
	body {
		background-image: url("image/cherry-blossom_00026.jpg");
		background-repeat: no-repeat;
	}
    .box {
      position: absolute;
      left: 20%;
      top: 10%;
      border: 1px solid #2f5fff;
      width: 50%;
      padding: 30px 20px;
      box-sizing: border-box;
      background: #ffffff;
    }
	</style>
 </head>
 <body>
  <br />
  <div class="box">
   <br />

    <div align="center">
     <img src="{{url('/image/NAT-TEST.png')}}">
     <label><h2 style="font-size:40px"><strong>ぶんぽう・どっかい</strong></h2></label><br />
     <label><h2 style="font-size:30px"><strong>しけんじかんは　７０　ぷん　です。</strong></h2></label><br />
     <button class = "buttonLogin" type="button" onclick="window.location='{{ url('/Q3ReadingQ1')}}'" class="middle-screen">はじめる</button>
    </div>
   
   <br />
  </div>
 </body>
</html>
