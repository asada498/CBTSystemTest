@inject('Q1S3Q5', 'App\Http\Controllers\Q1\Listening\Q1S3Q5Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q1ListeningQ5</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
    <script>
      function init()
      {
        var mondai5 = document.getElementById("mondai5");
		    mondai5.addEventListener("ended", function() { var ban1 = document.getElementById("ban1"); ban1.play(); });
		    var ban1 = document.getElementById("ban1");
        ban1.addEventListener("ended", function() { var audio1 = document.getElementById("audio1"); audio1.play(); });
		    var audio1 = document.getElementById("audio1");
        audio1.addEventListener("ended", function() { var ban2 = document.getElementById("ban2"); ban2.play(); });
		    var ban2 = document.getElementById("ban2");
        ban2.addEventListener("ended", function() { var audio2 = document.getElementById("audio2"); audio2.play(); });
		    var audio2 = document.getElementById("audio2");
        audio2.addEventListener("ended", function() { var mondai = document.getElementById("mondai"); mondai.play(); });
		    var mondai = document.getElementById("mondai");
        mondai.addEventListener("ended", function() { var ban3 = document.getElementById("ban3"); ban3.play(); });
		    var ban3 = document.getElementById("ban3");
        ban3.addEventListener("ended", function() { var audio3 = document.getElementById("audio3"); audio3.play(); });
		    var audio3 = document.getElementById("audio3");
        audio3.addEventListener("ended", function() { window.document.yourForm.submit(); });
      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          url:"{{ url('/saveChoiceRequestQ1S3Q5') }}",
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
<!-- 問題５、１番２番-->  
    <audio autoplay id="mondai5"> -->
	    <source src="{{ url('/audio/1Q/22-5/トラック41.mp3') }}">
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

    <!-- ３番と問題文-->
    <audio id="mondai" preload="none">
	    <source src="{{ url('/audio/1Q/22-5/トラック44.mp3') }}">
    </audio>

    <audio id="ban3" preload="none">
    <source src="{{ url('/audio/1Q/'.$data[2]->getBanFile()) }}">
	  </audio>
    <audio id="audio3" preload="none">
      <source src="{{ url('/audio/1Q/'.$data[2]->getListening()) }}">
	  </audio>

    <br/>
    <div class="question">
      <div class="question_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>５</div>
      <div class="question_right"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>５では<ruby><rb>長</rb><rt>なが</rt></ruby>めの<ruby><rb>話</rb><rt>はなし</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>きます。この<ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>には<ruby><rb>練</rb><rt>れん</rt><rb>習</rb><rt>しゅう</rt></ruby>はありません。</div>
    </div>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q1ListeningQ5SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">

            <div class="ban">
              <div class="ban_left">１<ruby><rb>番</rb><rt>ばん</rt></ruby>、２<ruby><rb>番</rb><rt>ばん</rt></ruby></div>
              <div class="ban_right"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt><rb>用</rb><rt>よう</rt><rb>紙</rb><rt>し</rt></ruby>に<ruby><rb>何</rb><rt>なに</rt></ruby>も<ruby><rb>印</rb><rt>いん</rt><rb>刷</rb><rt>さつ</rt></ruby>されていません。まず<ruby><rb>話</rb><rt>はなし</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いてください。それから<ruby><rb>質</rb><rt>しつ</rt><rb>問</rb><rt>もん</rt></ruby>と<ruby><rb>選</rb><rt>せん</rt><rb>択</rb><rt>たく</rt><rb>肢</rb><rt>し</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いて、ａ・ｂ・ｃ・ｄの<ruby><rb>中</rb><rt>なか</rt></ruby>から<ruby><rb>最</rb><rt>もっと</rt></ruby>もよいものを１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
            </div>

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
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}a" value="a" {{ $Q1S3Q5->checkValue($data[0]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}a">&nbsp a.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}b" value="b" {{ $Q1S3Q5->checkValue($data[0]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}b">&nbsp b.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}c" value="c" {{ $Q1S3Q5->checkValue($data[0]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}c">&nbsp c.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}d" value="d" {{ $Q1S3Q5->checkValue($data[0]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}d">&nbsp d.</label></span>
              <br><br>
              <p><span class="sec3-ban">２<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[1]->getQid()}} || Class_Listening: {{$data[1]->getGroup1()}} || Class_Relationship: {{$data[1]->getGroup2()}} || Class_Place:{{$data[1]->getGroup3()}} || new:{{$data[1]->getNewQuestion()}} || correct answer: {{$data[1]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}a" value="a" {{ $Q1S3Q5->checkValue($data[1]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}a">&nbsp a.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}b" value="b" {{ $Q1S3Q5->checkValue($data[1]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}b">&nbsp b.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}c" value="c" {{ $Q1S3Q5->checkValue($data[1]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}c">&nbsp c.</label></span>
              <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}d" value="d" {{ $Q1S3Q5->checkValue($data[1]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}d">&nbsp d.</label></span>
              <br><br>

              <div class="ban">
                <div class="ban_left">３<ruby><rb>番</rb><rt>ばん</rt></ruby></div>
                <div class="ban_right">まず<ruby><rb>話</rb><rt>はなし</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いてください。それから２つの<ruby><rb>質</rb><rt>しつ</rt><rb>問</rb><rt>もん</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いて、それぞれ<ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt><rb>用</rb><rt>よう</rt><rb>紙</rb><rt>し</rt></ruby>のａ・ｂ・ｃ・ｄの<ruby><rb>中</rb><rt>なか</rt></ruby>から<ruby><rb>最</rb><rt>もっと</rt></ruby>もよいものを１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
              </div>

              <p><span class="sec3-ban"><ruby><rb>質</rb><rt>しつ</rt><rb>問</rb><rt>もん</rt></ruby>１</span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[2]->getQid()}} || Class_Listening: {{$data[2]->getGroup1()}} || Class_Relationship: {{$data[2]->getGroup2()}} || Class_Place:{{$data[2]->getGroup3()}} || new:{{$data[2]->getNewQuestion()}} || correct answer: {{$data[2]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              @if ($data[2]->getRows() == 2)
              <span class="sec3-c2-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}a" value="a" {{ $Q1S3Q5->checkValue($data[2]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}a">&nbsp a. {!!$data[2]->getChoiceA()!!}</label></span>
              <span class="sec3-c2-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}b" value="b" {{ $Q1S3Q5->checkValue($data[2]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}b">&nbsp b. {!!$data[2]->getChoiceB()!!}</label></span>
              <span class="sec3-c2-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}c" value="c" {{ $Q1S3Q5->checkValue($data[2]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}c">&nbsp c. {!!$data[2]->getChoiceC()!!}</label></span>
              <span class="sec3-c2-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}d" value="d" {{ $Q1S3Q5->checkValue($data[2]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}d">&nbsp d. {!!$data[2]->getChoiceD()!!}</label></span>
              @else
              <span class="sec3-c1"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}a" value="a" {{ $Q1S3Q5->checkValue($data[2]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}a">&nbsp a. {!!$data[2]->getChoiceA()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}b" value="b" {{ $Q1S3Q5->checkValue($data[2]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}b">&nbsp b. {!!$data[2]->getChoiceB()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}c" value="c" {{ $Q1S3Q5->checkValue($data[2]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}c">&nbsp c. {!!$data[2]->getChoiceC()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}d" value="d" {{ $Q1S3Q5->checkValue($data[2]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}d">&nbsp d. {!!$data[2]->getChoiceD()!!}</label></span>
              @endif
              <br>
              <br>
              <p><span class="sec3-ban"><ruby><rb>質</rb><rt>しつ</rt><rb>問</rb><rt>もん</rt></ruby>２</span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[3]->getQid()}} || Class_Listening: {{$data[3]->getGroup1()}} || Class_Relationship: {{$data[3]->getGroup3()}} || Class_Place:{{$data[3]->getGroup1()}} || new:{{$data[3]->getNewQuestion()}} || correct answer: {{$data[3]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              @if ($data[3]->getRows() == 2)
              <span class="sec3-c2-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q1S3Q5->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a. {!!$data[3]->getChoiceA()!!}</label></span>
              <span class="sec3-c2-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q1S3Q5->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b. {!!$data[3]->getChoiceB()!!}</label></span>
              <span class="sec3-c2-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q1S3Q5->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c. {!!$data[3]->getChoiceC()!!}</label></span>
              <span class="sec3-c2-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}d" value="d" {{ $Q1S3Q5->checkValue($data[3]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}d">&nbsp d. {!!$data[3]->getChoiceD()!!}</label></span>
              @else
              <span class="sec3-c1"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q1S3Q5->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a. {!!$data[3]->getChoiceA()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q1S3Q5->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b. {!!$data[3]->getChoiceB()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q1S3Q5->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c. {!!$data[3]->getChoiceC()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}d" value="d" {{ $Q1S3Q5->checkValue($data[3]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}d">&nbsp d. {!!$data[3]->getChoiceD()!!}</label></span>
              @endif
            </div>
          </div>
        </form> 
      </div>
    </div>
  </body>
</html>