<html>
  <head>
    <title>Q3S3 VOLUME TEST</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}" >
    <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
    <style type="text/css">
      .btn {
        padding: 15px 32px;
        background-color: #EC965E ;
        font-weight: bold;
        border-style: solid;
        border-width: 2px;
        border-color: black;
        border-radius: 0;
      }
      .center { text-align: center; }
    </style>  
  </head>

  <body>
    <audio id="audio" >
        <source  src="{{ asset('Audio/5Q/20-2/01 Track01.mp3') }}" type="audio/mp3" preload="auto"/>
    </audio>
    <div class="center">
      <br><br><br><br><br><br><br><br><br>
      <label><h1 style="font-size:50px"><strong>これから　もんだいを　はじめます。</strong></h1></label>
      <br>
      <label><h1 style="font-size:50px"><strong>ヘッドホンを　つけてください。</strong></h1></label>
      <br><br>
      <button class="btn" id="btn1 center" type="button" onclick="playAudio();" class="middle-screen">つけました</button>
      <br><br><br>
      <div class ="btn2">
        <label><h1 style="font-size:50px"><strong>きこえない　ひとは　てを　あげてください。</strong></h1></label>
        <br><br>
        <label><h1 style="font-size:50px"><strong>ききながら　メモを　とっても　いいです。</strong></h1></label>
        <br><br>
        <button class="btn" id="btn2" type="button" onclick="window.location='{{ url('/Q2ListeningQ1') }}'" class="middle-screen" >よく　きこえます</button>
      </div>
      
    </div>
    <script> 
      function playAudio() {
          document.querySelector('.btn2').style.display = 'block';
          var x = document.getElementById("audio"); 
          x.play();
      }
      document.querySelector('.btn2').style.display = 'none'; 
    </script>
  </body>
</html>