<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>受験申込</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
 </head>
 <body>

   <h1 align="center"><ruby><rb>受</rb><rt>じゅ</rt><rb>験</rb><rt>けん</rt><rb>申</rb><rt>もうし</rt><rb>込</rb><rt>こみ</rt></ruby></h1>
   <h1 align="center">Application for CBT_NAT-TEST</h1>
   <br><br>
   <div style="font-size: 40px;">
      　あなたのメールアドレスをご<ruby><rb>記</rb><rt>き</rt><rb>入</rb><rt>にゅう</rt></ruby>ください。<br>
      　Enter your email address.
   </div>
   <br>
   <form method="post" action="{{ url('/sendmail') }}">
      {{ csrf_field() }}
      　　　
      <input type="email" name="address" required style="width: 600px; font-size: 30px;"/>　
      <button type="submit" style="font-size: 30px;"/>　　<ruby><rb>送</rb><rt>そう</rt><rb>信</rb><rt>しん</rt></ruby>　Send　　</button>
   </form>
   <br>
   <div style="font-size: 40px;">
      　<ruby><rb>折</rb><rt>お</rt></ruby>り<ruby><rb>返</rb><rt>かえ</rt></ruby>し<ruby><rb>受</rb><rt>じゅ</rt><rb>験</rb><rt>けん</rt><rb>申</rb><rt>もうし</rt><rb>込</rb><rt>こみ</rt></ruby>のフォーマットをメールします。<br>
      　You will receive application format via email.
   </div>

 </body>
</html>