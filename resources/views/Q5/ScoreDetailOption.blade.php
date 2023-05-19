


<!DOCTYPE html>
<html>
 <head>
  <title></title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}" >

 
 
</head>
<script>
function myFunction(id) {
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type:'POST',
      url:'/saveChoiceRequestTestResultQ5',
      data:{name:id.name, choice:id.value},
      success:function(data)
      {
        // alert(data.success);
      }
    }); 

  }

function goBack() {
  window.history.back();
}

</script>
<body>
  <br />
  <button  class="buttonLogin testFlex" onclick="goBack()"> いんさつに　もどる </button>

  <div class="container boxForOpening">
   <br/>


    <div align="left" class="background-gradeoption-page">
        <form method="post" action="{{ url('/Q5gradecertificatechoiceSubmitData')}}" name="yourForm">
          {{ csrf_field() }}

         <label><h1 style="font-size:40px"><strong>もし　あなたが　<ruby>合格<rt>ごうかく</rt></ruby>していれば</strong></h1></label><br />
        

  <h2 style="font-size:30px"><strong><ruby>成績<rt>せいせき</rt></ruby><ruby>票<rt>ひょう</rt></ruby> が <ruby>必要<rt>ひつよう</rt></ruby> </label> &emsp;&emsp;&emsp; 
   
     
     <label><input type="radio" name ="grade" id ="gradeyes" onclick=" myFunction(this)" for ="gradeyes"value ="gradeyes" required >&nbspYES.</label>&nbsp;
     &nbsp; &nbsp;<label><input type="radio" name="grade" id ="gradeno" onclick=" myFunction(this)"  for ="gradeno"value ="gradeno" >&nbsp NO.</label> </strong></h2>  </label>
   
<br >
 <div style="font-size:18px">※<ruby>無料<rt>むりょう</rt></ruby>です<div>
<br> 
<br>
<br>
     <h2 style="font-size:30px"><strong> <ruby>合格<rt>ごうかく </rt></ruby><ruby>票<rt>ひょう</rt></ruby> が <ruby>必要<rt>ひつよう</rt></ruby> &emsp;&emsp;&emsp; 
   

      <label><input type="radio" name ="certificate" id ="certificateyes" for ="certificateyes"onclick=" myFunction(this)" value ="certificateyes" required >&nbspYES.</label>&nbsp;
      &nbsp; &nbsp;<label><input type="radio" name="certificate" id ="certificateno" for ="certificateno" onclick=" myFunction(this)" value ="certificateno" >&nbsp NO.</label> </strong></h2></label>
  <br>
  <div style="font-size:18px">  ※<ruby>有料<rt>ゆうりょう</rt></ruby>です</div>
<br>
    
     <input  type="submit" name="yourForm" class="buttonLogin" value="つぎの　がめんへ" />
    </div>
    
   <br/>
  </div>

  
</form>
</body>
</html>
