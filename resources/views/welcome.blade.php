<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php
    $password = $_SERVER['REMOTE_USER'];   
    //$password = "aikkamata2255";
    Session::put('password', $password);
?>
<div class="alert alert-success">
  Administration system
</div>
  
@if($password=="aikkamata2255" or $password=="tokyo")
<div class="container-fluid">
  <div class="row" style="text-align: center; margin-top:50px;">
    <div class="col-sm-3">
        <a href="https://drive.google.com/drive/u/1/folders/1vzs3Xvk0E5yruN2fkeLc9NDKABD2qdJU" target="_blank" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-primary">受付状況<br>Application status</a>
    </div>
    <div class="col-sm-3">
        <form method="post" action="{{ url('/admin/menuPush') }}">
          {{ csrf_field() }}
                  <button type="submit" formtarget="_blank" name="mode" value="edit" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-secondary">受験者管理<br>Examinee's info</button><br><br>
        </form>
    </div>
    <div class="col-sm-3">
        <a href="https://drive.google.com/drive/u/1/folders/11lbxQMHaselokyGmor81Z4MjMXrDbCl5" target="_blank" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-success">写真参照 <br>Picture</a>
    </div> 
    <div class="col-sm-3">
        <form method="post" action="{{ url('/grade/checklogin') }}">
        {{ csrf_field() }}
          <button type="submit" formtarget="_blank" name="login" value="Login" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-info">成績参照 <br>Grade</button>
        </form>
        
    </div>     
  </div>
  <div class="row" style="text-align: center; margin-top:50px;">
    <div class="col-sm-3">
        <a href="{{ url('mail') }}" target="_blank" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-primary">受験申込<br>Request</a>
    </div>
    <div class="col-sm-3">
        <a href="{{ url('main') }}" target="_blank" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-secondary">受験アクセス<br>Test Start</a>
    </div>
    <div class="col-sm-3">
        <form method="post" action="{{ url('/admin/supervisorMenuPush') }}">
        {{ csrf_field() }}
                <button formtarget="_blank"style="font-size: 20px; width: 200px;" type="submit" class="btn btn-outline-success" name="mode" value="progress">トラブル <br>Trouble</button>
        </form>
    </div> 
    <div class="col-sm-3">
        <form method="post" action="{{ url('/answerDownload/checklogin') }}">
        {{ csrf_field() }}
          <button type="submit" formtarget="_blank" name="login" value="Login" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-info">回答参照 <br>Answer</button>
        </form>
    </div>     
  </div>
</div>
@else
<div class="container-fluid">
  <div class="row" style="text-align: center; margin-top:50px;">
    <div class="col-sm-4">
        <a href="{{ url('excelinfo/'.$password.'/') }}" target="_blank" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-primary">受付状況<br>Application</a>
    </div>
    <div class="col-sm-4">
          <form method="post" action="{{ url('/admin/menuPush') }}">
          {{ csrf_field() }}
                  <button type="submit" formtarget="_blank" name="mode" value="edit" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-secondary">受験者管理<br>Testee's info</button><br><br>
        </form>
    </div>
    <div class="col-sm-4">
        <a href="https://drive.google.com/drive/u/1/folders/11lbxQMHaselokyGmor81Z4MjMXrDbCl5" target="_blank" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-success">写真参照 <br>Picture</a>
    </div>    
  </div>
  <div class="row" style="text-align: center; margin-top:50px;">
    <div class="col-sm-4">
        <a href="{{ url('mail') }}" target="_blank" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-primary">受験申込<br>Request</a>
    </div>
    <div class="col-sm-4">
        <a href="{{ url('main1') }}" target="_blank" style="font-size: 20px; width: 200px;" type="button" class="btn btn-outline-secondary">受験アクセス<br>Test Start</a>
    </div>
    <div class="col-sm-4">
    <form method="post" action="{{ url('/admin/supervisorMenuPush') }}">
        {{ csrf_field() }}
                <button formtarget="_blank"style="font-size: 20px; width: 200px;" type="submit" class="btn btn-outline-success" name="mode" value="progress">トラブル <br>Trouble</button>
        </form>
    </div>     
  </div>
</div>
@endif
@if($password=="aikkamata2255")
<div class="container" style="background-color:aliceblue; border-radius:20px">
  <div class="row" style="text-align: center; margin-top:50px;">
    <div class="col-sm-6">
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/hiragana/main" target="_blank" style="font-size: 25px; color:darkcyan;" >ひらがなの勉強</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/katakana/main" target="_blank" style="font-size: 25px; color:darkcyan;" >カタカナの勉強</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/kanji5/main" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字５級の勉強</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/kanji4/main" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字４級の勉強</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字３級の勉強（工事中）</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字２級の勉強（工事中）</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字１級の勉強（工事中）</a></div>
    </div>
    <div class="col-sm-6" style="border-left-style: solid; border-left-color: coral">
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/hiragana/admin" target="_blank" style="font-size: 25px; color:darkcyan;" >ひらがな管理</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/katakana/admin" target="_blank" style="font-size: 25px; color:darkcyan;" >カタカナ管理</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/kanji5/admin" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字５級管理</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/kanji4/admin" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字４級管理</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/kanji3/admin" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字３級管理</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/kanji2/admin" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字２級管理</a></div>
        <div style="margin-top: 20px;"><a href="https://www.senmonkyouiku.com/kanji1/admin" target="_blank" style="font-size: 25px; color:darkcyan;" >漢字１級管理</a></div>
    </div>
  </div>
</div>
@endif

</body>
</html>
