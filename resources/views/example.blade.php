<!DOCTYPE html>
<html>
 <head>
  <title>Example</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style type="text/css">
   .box{
    width:1200px;
    margin:0 auto;
    border:1px solid #ccc;
    font-size:19px;
    padding-top:20px;
   }
   .test{
     margin-bottom:30px;
     margin-top:15px;
   }
   .c1{
    display:inline-block;
    width: 15%;
    padding-left:1.5%;
   }
   .c3{
    display:inline-block;
    width: 10%;
    padding-left:3%;
   }
   .c2{
    display:inline-block;
    width: 5%;
   }
   .title1{
      text-align:center;
   }
   .previous{
    display:inline-block;
    width: 49%;
    padding-bottom:30px;
   }
   .buttonSize{
    height:30px;
    width:100px;
   }
   .example{
       padding-left:5%;
   }
   .column {
  float: left;
  width: 15%;
  padding: 10px;
}
.column1 {
  float: left;
  width: 80%;
  padding: 10px;
}
.row:after {
  content: "";
  display: table;
  clear: both;
}
.containerTest{
  width: 100%;

}
label {
    font-weight: normal !important;
}
  </style>
 </head>
 <body>
  <div class = "title1">          <img src="{{ asset('image/N11152060100_1.jpg') }}"  style="width:800px"alt="Image"/></div>
 <div class = "title1">          <img src="{{ asset('image/N11152060100_2.jpg') }}"  style="width:800px"alt="Image"/></div>

  <br />
<div class = "title1">  <h1>5きゅう　げんごちしき　（もじ・ごい）</h1></div>
  <div class = "title1">  <h4>--------------------------------</h1></div>
  <div class="container">
    <div class="column" >
        <div class = "title1">  <h1>もんだい１</h1></div>
    </div>
    <div class="column1">
        <div>  <h2>（　　）に　<ruby><rb>何</rb><rt>なに</rt></ruby>を　<ruby><rb>入</rb><rt>い</rt></ruby>れますか。ａ・ｂ・ｃ・ｄから　いちばん　いい　ものを　<ruby><rb>一</rb><rt>ひと</rt></ruby>つ　えらんで　ください。</h2></div>
    </div>
   </div>
   <div class="container box">
   <div>  <h4><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>れい　。。。。。。<ruby><rb>答</rb><rt>こた</rt></ruby>え<ruby><rb>方</rb><rt>かた</rt></ruby></h4></div>

</div>
  <div class="container box">
  <!-- <ruby><rb>一</rb><rt>いち</rt><rb>日</rb><rt>にち</rt><rb>中</rb><rt>じゅう</rt></ruby><ruby><rb>晴</rb><rt>は</rt></ruby>れていて、<ruby><rb>暑</rb><rt>あつ</rt></ruby>い。 -->
  <h3>きょうは　いい　てんきです。</h3>

   <div class = "test">
   <p><span><h3>水　　　　　日　　　　月　　　　　火　　　<h3>　</span></p>
   <p><u>　　　</u>　<u>　　　</u>　<u>きょうは　いい　てんきです。</u>　<u>　　　</u>　ですか。</p>
        <span class = "c1"><input type="radio" name="a" id = "a" value="a"><label for="a">&nbsp a. きょうは　はれです。</label></span>
        <span class = "c1"><input type="radio" name="b" id = "b" value="b"><label for="a">&nbsp b. きょうは　あめです。</label></span>
        <span class = "c1"><input type="radio" name="c" id = "c" value="c"><label for="a">&nbsp c. きょうは　くもりです。</label></span>
        <span class = "c1"><input type="radio" name="d" id = "d" value="d"><label for="a">&nbsp d. きょうは　ゆきです。</label></span>
    <p><span>（こたえかた）</span></p>
    <p>ただしい　ぶんを　つくります。</p>
    <p><u>　ｄ.　この　</u>　<u>　ｃ.　かさ　</u>　<u>　★ｂ.　は　</u>　<u>　ａ.　だれの　</u>　ですか。</p>

        <span class = "c3">こたえ</span>
        <span class = "c2"><input type="radio" name="a" id = "a" value="a"><label for="a">&nbsp a</label></span>
        <span class = "c2"><input type="radio" name="a" id = "a" value="a" checked ><label for="a">&nbsp b</label></span>
        <span class = "c2"><input type="radio" name="a" id = "a" value="a"><label for="a">&nbsp c</label></span>
        <span class = "c2"><input type="radio" name="a" id = "a" value="a"><label for="a">&nbsp d</label></span>

      </div> 
      </div>
      <div class = "title1">

      <div>  <h3>There are 4 parts in the Vocabulary section。You will be given seperate time for each section. </h3></div>
        <div>  <h3>Please answer each sections by the end of time. You cannot back to previous section, so remember to answer everything.</h3></div>
        <div>  <h3>Press Continue to start the first section.</h3></div>


  </div>
  <div class = "title1">
  <button type="button" onclick="window.location='{{ url('/Q5VocabularyQ2')}}'" class="middle-screen">Continue</button>
    </div>

 </body>
</html>

<!--  -->