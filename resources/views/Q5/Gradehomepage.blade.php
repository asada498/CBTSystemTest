<!DOCTYPE html>
<html>
 <head>
  <title></title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}" >
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
</head>
<body>
  <br />
  <div class="container boxForOpening">
   <br />

    <div align="center" class="background-welcome-page">
     <label><h1 style="font-size:50px"><strong>５<ruby>級<rt>きゅう<rt></ruby>の　テストは 　<ruby>終<rt>お</rt></ruby>わりました。</strong></h1></label><br />
     <!-- <label><h2 style="font-size:40px"><strong><ruby>今日<rt>きょう</rt></ruby>の　<ruby>成績<rt>せいせき</rt>を　プリントアウト 　してください。<ruby></strong></h2></label><br /> -->
     <label><h2 style="font-size:40px"><strong>お<ruby>帰<rt>かえ</rt></ruby>り　ください。</strong></h2></label><br />
     <label><h2 style="font-size:30px"><strong></strong></h2></label><br/>
     <button class = "buttonLogin" type="button" onclick="window.location='{{ url('/Q5TestResult')}}'">ログイン　がめんへ</button>
    </div>
   <br />
  </div>
  
</body>
</html>
