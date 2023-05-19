<!DOCTYPE html>
<html>
 <head>
  <title>Q5S3 START</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}" >
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

    <div align="center" >
     <img src="{{url('/image/NAT-TEST.png')}}">
<!--
     <img src="/nat-test/image/NAT-TEST.png">
-->
     <label><h2 style="font-size:40px"><strong>ちょうかい</strong></h2></label><br />
     <label><h2 style="font-size:30px"><strong>しけんじかんは　３０　ぷん　です。</strong></h2></label><br />
     <button class = "buttonLogin" type="button" onclick="window.location='{{ url('/Q5S3VolumeTest')}}'">はじめる</button>
    </div>
   <br />
  </div>
</body>
</html>
