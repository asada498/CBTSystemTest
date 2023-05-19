@inject('Q2S3Q2', 'App\Http\Controllers\Q2\Listening\Q2S3Q2N1Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q2ListeningQ2</title>
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
        audio4.addEventListener("ended", function() { window.location.href = "{{ url('/Q2ListeningQ2N5') }}"; });
      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          url:"{{ url('/saveChoiceRequestQ2S3Q2N1') }}",
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
	  <source src="{{ url('/audio/3Q/22-3/Track 10.mp3') }}">   <!-- TODO とりあえず３級22-3が最新なので-->
    </audio>
    <audio id="ban1" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[0]->getBanFile()) }}">
	  </audio>
    <audio id="audio1" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[0]->getListening()) }}">
	  </audio>
    <audio id="ban2" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[1]->getBanFile()) }}">
	  </audio>
    <audio id="audio2" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[1]->getListening()) }}">
	  </audio>
    <audio id="ban3" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[2]->getBanFile()) }}">
	  </audio>
    <audio id="audio3" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[2]->getListening()) }}">
	  </audio>
    <audio id="ban4" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[3]->getBanFile()) }}">
	  </audio>
    <audio id="audio4" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[3]->getListening()) }}">
	  </audio>

    <br/>
    <div class="question">
      <div class="question_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>２</div>
      <div class="question_right"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>２では、まず<ruby><rb>質</rb><rt>しつ</rt><rb>問</rb><rt>もん</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いてください。そのあと、<ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt><rb>用</rb><rt>よう</rt><rb>紙</rb><rt>し</rt></ruby>を<ruby><rb>見</rb><rt>み</rt></ruby>てください。<ruby><rb>読</rb><rt>よ</rt></ruby>む<ruby><rb>時</rb><rt>じ</rt><rb>間</rb><rt>かん</rt></ruby>があります。それから<ruby><rb>話</rb><rt>はなし</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いて<br>ａ・ｂ・ｃ・ｄの<ruby><rb>中</rb><rt>なか</rt></ruby>からもっともよいものを１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
    </div>
    <div class = "title1">
      <img src="{{ asset('image/2Q/example/q2s3q2example.png') }}" alt="Image"/>
    </div>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q2ListeningQ2SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">
              <p><span class="sec3-ban">１<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.location='{{ url('/Q2ListeningQ2N5') }}'" style="vertical-align: right;">skip</button>
@endif
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[0]->getQid()}} || Class_Listening: {{$data[0]->getGroup1()}} || Class_Relationship: {{$data[0]->getGroup2()}} || Class_Place:{{$data[0]->getGroup3()}} ||  anchor:{{$data[0]->getAnchor()}} || correct answer: {{$data[0]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              @if ($data[0]->getRows() == 2)
              <span class="sec3-c2"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}a" value="a" {{ $Q2S3Q2->checkValue($data[0]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}a">&nbsp a. {!!$data[0]->getChoiceA()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}b" value="b" {{ $Q2S3Q2->checkValue($data[0]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}b">&nbsp b. {!!$data[0]->getChoiceB()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}c" value="c" {{ $Q2S3Q2->checkValue($data[0]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}c">&nbsp c. {!!$data[0]->getChoiceC()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}d" value="d" {{ $Q2S3Q2->checkValue($data[0]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}d">&nbsp d. {!!$data[0]->getChoiceD()!!}</label></span>
              @else
              <span class="sec3-c1"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}a" value="a" {{ $Q2S3Q2->checkValue($data[0]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}a">&nbsp a. {!!$data[0]->getChoiceA()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}b" value="b" {{ $Q2S3Q2->checkValue($data[0]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}b">&nbsp b. {!!$data[0]->getChoiceB()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}c" value="c" {{ $Q2S3Q2->checkValue($data[0]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}c">&nbsp c. {!!$data[0]->getChoiceC()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}d" value="d" {{ $Q2S3Q2->checkValue($data[0]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}d">&nbsp d. {!!$data[0]->getChoiceD()!!}</label></span>
              @endif
              <br><br>
              <p><span class="sec3-ban">２<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[1]->getQid()}} || Class_Listening: {{$data[1]->getGroup1()}} || Class_Relationship: {{$data[1]->getGroup2()}} || Class_Place:{{$data[1]->getGroup3()}} ||  anchor:{{$data[1]->getAnchor()}} || correct answer: {{$data[1]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              @if ($data[1]->getRows() == 2)
              <span class="sec3-c2"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}a" value="a" {{ $Q2S3Q2->checkValue($data[1]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}a">&nbsp a. {!!$data[1]->getChoiceA()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}b" value="b" {{ $Q2S3Q2->checkValue($data[1]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}b">&nbsp b. {!!$data[1]->getChoiceB()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}c" value="c" {{ $Q2S3Q2->checkValue($data[1]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}c">&nbsp c. {!!$data[1]->getChoiceC()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}d" value="d" {{ $Q2S3Q2->checkValue($data[1]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}d">&nbsp d. {!!$data[1]->getChoiceD()!!}</label></span>
              @else
              <span class="sec3-c1"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}a" value="a" {{ $Q2S3Q2->checkValue($data[1]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}a">&nbsp a. {!!$data[1]->getChoiceA()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}b" value="b" {{ $Q2S3Q2->checkValue($data[1]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}b">&nbsp b. {!!$data[1]->getChoiceB()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}c" value="c" {{ $Q2S3Q2->checkValue($data[1]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}c">&nbsp c. {!!$data[1]->getChoiceC()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}d" value="d" {{ $Q2S3Q2->checkValue($data[1]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}d">&nbsp d. {!!$data[1]->getChoiceD()!!}</label></span>
              @endif
              <br><br>
              <p><span class="sec3-ban">３<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[2]->getQid()}} || Class_Listening: {{$data[2]->getGroup1()}} || Class_Relationship: {{$data[2]->getGroup2()}} || Class_Place:{{$data[2]->getGroup3()}} ||  anchor:{{$data[2]->getAnchor()}} || correct answer: {{$data[2]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              @if ($data[2]->getRows() == 2)
              <span class="sec3-c2"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}a" value="a" {{ $Q2S3Q2->checkValue($data[2]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}a">&nbsp a. {!!$data[2]->getChoiceA()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}b" value="b" {{ $Q2S3Q2->checkValue($data[2]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}b">&nbsp b. {!!$data[2]->getChoiceB()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}c" value="c" {{ $Q2S3Q2->checkValue($data[2]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}c">&nbsp c. {!!$data[2]->getChoiceC()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}d" value="d" {{ $Q2S3Q2->checkValue($data[2]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}d">&nbsp d. {!!$data[2]->getChoiceD()!!}</label></span>
              @else
              <span class="sec3-c1"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}a" value="a" {{ $Q2S3Q2->checkValue($data[2]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}a">&nbsp a. {!!$data[2]->getChoiceA()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}b" value="b" {{ $Q2S3Q2->checkValue($data[2]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}b">&nbsp b. {!!$data[2]->getChoiceB()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}c" value="c" {{ $Q2S3Q2->checkValue($data[2]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}c">&nbsp c. {!!$data[2]->getChoiceC()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}d" value="d" {{ $Q2S3Q2->checkValue($data[2]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}d">&nbsp d. {!!$data[2]->getChoiceD()!!}</label></span>
              @endif
              <br><br>
              <p><span class="sec3-ban">４<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[3]->getQid()}} || Class_Listening: {{$data[3]->getGroup1()}} || Class_Relationship: {{$data[3]->getGroup2()}} || Class_Place:{{$data[3]->getGroup3()}} ||  anchor:{{$data[3]->getAnchor()}} || correct answer: {{$data[3]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              @if ($data[3]->getRows() == 2)
              <span class="sec3-c2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q2S3Q2->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a. {!!$data[3]->getChoiceA()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q2S3Q2->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b. {!!$data[3]->getChoiceB()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q2S3Q2->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c. {!!$data[3]->getChoiceC()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}d" value="d" {{ $Q2S3Q2->checkValue($data[3]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}d">&nbsp d. {!!$data[3]->getChoiceD()!!}</label></span>
              @else
              <span class="sec3-c1"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q2S3Q2->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a. {!!$data[3]->getChoiceA()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q2S3Q2->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b. {!!$data[3]->getChoiceB()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q2S3Q2->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c. {!!$data[3]->getChoiceC()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}d" value="d" {{ $Q2S3Q2->checkValue($data[3]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}d">&nbsp d. {!!$data[3]->getChoiceD()!!}</label></span>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>