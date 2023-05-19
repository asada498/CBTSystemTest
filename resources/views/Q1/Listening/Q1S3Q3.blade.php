@inject('Q1S3Q3', 'App\Http\Controllers\Q1\Listening\Q1S3Q3Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q1ListeningQ3</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
    <script>
      function init()
      {
        var sampleAudio = document.getElementById("sampleAudio");
		    sampleAudio.addEventListener("ended", function() { var ban1 = document.getElementById("ban1"); ban1.play(); });
		    var ban1 = document.getElementById("ban1");
        ban1.addEventListener("ended", function() { var audio1 = document.getElementById("audio1"); audio1.play(); });
		    var audio1 = document.getElementById("audio1");
        audio1.addEventListener("ended", function() { var ban2 = document.getElementById("ban2"); ban2.play(); });
		    var ban2 = document.getElementById("ban2");
        ban2.addEventListener("ended", function() { var audio2 = document.getElementById("audio2"); audio2.play(); });
		    var audio2 = document.getElementById("audio2");
        audio2.addEventListener("ended", function() { var ban3 = document.getElementById("ban3"); ban3.play(); });
		    var ban3 = document.getElementById("ban3");
        ban3.addEventListener("ended", function() { var audio3 = document.getElementById("audio3"); audio3.play(); });
		    var audio3 = document.getElementById("audio3");
        audio3.addEventListener("ended", function() { var ban4 = document.getElementById("ban4"); ban4.play(); });
		    var ban4 = document.getElementById("ban4");
        ban4.addEventListener("ended", function() { var audio4 = document.getElementById("audio4"); audio4.play(); });
        var audio4 = document.getElementById("audio4");
        audio4.addEventListener("ended", function() { var ban5 = document.getElementById("ban5"); ban5.play(); });
        var ban5 = document.getElementById("ban5");
        ban5.addEventListener("ended", function() { var audio5 = document.getElementById("audio5"); audio5.play(); });
        var audio5 = document.getElementById("audio5");
        audio5.addEventListener("ended", function() { var ban6 = document.getElementById("ban6"); ban6.play(); });
        var ban6 = document.getElementById("ban6");
        ban6.addEventListener("ended", function() { var audio6 = document.getElementById("audio6"); audio6.play(); });
        var audio6 = document.getElementById("audio6");
        audio6.addEventListener("ended", function() { window.document.yourForm.submit(); });
      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          url:"{{ url('/saveChoiceRequestQ1S3Q3') }}",
          data:{name:id.name, answer:id.value},
          success:function(data)
          {
            // alert(data.success);
          }
        });
      }
    </script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  </head>
  <body onload="init();">
    <audio autoplay id="sampleAudio">
	  <source src="{{ url('/audio/3Q/22-3/Track 18.mp3') }}">  <!-- TODO 1Q-->
    </audio>
    <audio id="ban1" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[0]->getBanFile()) }}">
	  </audio>
    <audio id="audio1" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[0]->getListening()) }}">
	  </audio>
    <audio id="ban2" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[1]->getBanFile()) }}">
	  </audio>
    <audio id="audio2" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[1]->getListening()) }}">
	  </audio>
    <audio id="ban3" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[2]->getBanFile()) }}">
	  </audio>
    <audio id="audio3" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[2]->getListening()) }}">
	  </audio>
    <audio id="ban4" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[3]->getBanFile()) }}">
	  </audio>
    <audio id="audio4" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[3]->getListening()) }}">
	  </audio>
    <audio id="ban5" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[4]->getBanFile()) }}">
	  </audio>
    <audio id="audio5" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[4]->getListening()) }}">
	  </audio>
    <audio id="ban6" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[5]->getBanFile()) }}">
	  </audio>
    <audio id="audio6" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[5]->getListening()) }}">
	  </audio>

    <br/>
    <div class="question">
      <div class="question_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>３</div>
      <div class="question_right"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>３では<ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt><rb>用</rb><rt>よう</rt><rb>紙</rb><rt>し</rt></ruby>に<ruby><rb>何</rb><rt>なに</rt></ruby>も<ruby><rb>印</rb><rt>いん</rt><rb>刷</rb><rt>さつ</rt></ruby>されていません。この<ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>は<ruby><rb>全</rb><rt>ぜん</rt><rb>体</rb><rt>たい</rt></ruby>としてどんな<ruby><rb>内</rb><rt>ない</rt><rb>容</rb><rt>よう</rt></ruby>かを<ruby><rb>聞</rb><rt>き</rt></ruby>く<ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>です。<ruby><rb>話</rb><rt>はなし</rt></ruby>の<ruby><rb>前</rb><rt>まえ</rt></ruby>に<ruby><rb>質</rb><rt>しつ</rt><rb>問</rb><rt>もん</rt></ruby>はありません。まず<ruby><rb>話</rb><rt>はなし</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いてください。それから<ruby><rb>質</rb><rt>しつ</rt><rb>問</rb><rt>もん</rt></ruby>と<ruby><rb>選</rb><rt>せん</rt><rb>択</rb><rt>たく</rt><rb>肢</rb><rt>し</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いて、ａ・ｂ・ｃ・ｄの<ruby><rb>中</rb><rt>なか</rt></ruby>からもっともよいものを１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
    </div>
    <div class = "title1">
      <img src="{{ asset('image/1Q/example/q1s3q3example.png') }}" alt="Image"/>
    </div>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q1ListeningQ3SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">
              <p><span class="sec3-ban">１<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.document.yourForm.submit();" style="vertical-align: right;">skip</button>
@endif
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[0]->getQid()}} || Class_Listening: {{$data[0]->getGroup1()}} || Class_Relationship: {{$data[0]->getGroup2()}} || Class_Place:{{$data[0]->getGroup3()}} ||  new:{{$data[0]->getNewQuestion()}} || correct answer: {{$data[0]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}a" value="a" {{ $Q1S3Q3->checkValue($data[0]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}a">&nbsp a.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}b" value="b" {{ $Q1S3Q3->checkValue($data[0]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}b">&nbsp b.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}c" value="c" {{ $Q1S3Q3->checkValue($data[0]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}c">&nbsp c.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}d" value="d" {{ $Q1S3Q3->checkValue($data[0]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}d">&nbsp d.</label></span>
              <br><br>
              <p><span class="sec3-ban">２<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[1]->getQid()}} || Class_Listening: {{$data[1]->getGroup1()}} || Class_Relationship: {{$data[1]->getGroup2()}} || Class_Place:{{$data[1]->getGroup3()}} || new:{{$data[1]->getNewQuestion()}} || correct answer: {{$data[1]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}a" value="a" {{ $Q1S3Q3->checkValue($data[1]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}a">&nbsp a.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}b" value="b" {{ $Q1S3Q3->checkValue($data[1]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}b">&nbsp b.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}c" value="c" {{ $Q1S3Q3->checkValue($data[1]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}c">&nbsp c.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}d" value="d" {{ $Q1S3Q3->checkValue($data[1]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}d">&nbsp d.</label></span>
              <br><br>
              <p><span class="sec3-ban">３<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[2]->getQid()}} || Class_Listening: {{$data[2]->getGroup1()}} || Class_Relationship: {{$data[2]->getGroup2()}} || Class_Place:{{$data[2]->getGroup3()}} || new:{{$data[2]->getNewQuestion()}} || correct answer: {{$data[2]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}a" value="a" {{ $Q1S3Q3->checkValue($data[2]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}a">&nbsp a.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}b" value="b" {{ $Q1S3Q3->checkValue($data[2]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}b">&nbsp b.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}c" value="c" {{ $Q1S3Q3->checkValue($data[2]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}c">&nbsp c.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}d" value="d" {{ $Q1S3Q3->checkValue($data[2]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}d">&nbsp d.</label></span>
              <br><br>
              <p><span class="sec3-ban">４<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[3]->getQid()}} || Class_Listening: {{$data[3]->getGroup1()}} || Class_Relationship: {{$data[3]->getGroup2()}} || Class_Place:{{$data[3]->getGroup3()}} || new:{{$data[3]->getNewQuestion()}} || correct answer: {{$data[3]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q1S3Q3->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q1S3Q3->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q1S3Q3->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}d" value="d" {{ $Q1S3Q3->checkValue($data[3]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}d">&nbsp d.</label></span>
              <br><br>
              <p><span class="sec3-ban">５<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[4]->getQid()}} || Class_Listening: {{$data[4]->getGroup1()}} || Class_Relationship: {{$data[4]->getGroup2()}} || Class_Place:{{$data[4]->getGroup3()}} || new:{{$data[4]->getNewQuestion()}} || correct answer: {{$data[4]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}a" value="a" {{ $Q1S3Q3->checkValue($data[4]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}a">&nbsp a.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}b" value="b" {{ $Q1S3Q3->checkValue($data[4]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}b">&nbsp b.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}c" value="c" {{ $Q1S3Q3->checkValue($data[4]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}c">&nbsp c.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}d" value="d" {{ $Q1S3Q3->checkValue($data[4]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}d">&nbsp d.</label></span>
              <br><br>
              <p><span class="sec3-ban">６<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[5]->getQid()}} || Class_Listening: {{$data[5]->getGroup1()}} || Class_Relationship: {{$data[5]->getGroup2()}} || Class_Place:{{$data[5]->getGroup3()}} || new:{{$data[5]->getNewQuestion()}} || correct answer: {{$data[5]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q1S3Q3->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q1S3Q3->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q1S3Q3->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}d" value="d" {{ $Q1S3Q3->checkValue($data[5]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}d">&nbsp d.</label></span>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>