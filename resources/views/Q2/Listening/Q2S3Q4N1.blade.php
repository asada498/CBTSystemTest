@inject('Q2S3Q4', 'App\Http\Controllers\Q2\Listening\Q2S3Q4N1Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q2ListeningQ4</title>
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
        audio5.addEventListener("ended", function() { window.location.href = "{{ url('/Q2ListeningQ4N2') }}"; });

      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          url:"{{ url('/saveChoiceRequestQ2S3Q4N1') }}",
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
	  <source src="{{ url('/audio/2Q/22-3/トラック23.mp3') }}">
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
    <audio id="ban5" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[4]->getBanFile()) }}">
	  </audio>
    <audio id="audio5" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[4]->getListening()) }}">
	  </audio>
    <br/>
    <div class="question">
      <div class="question_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>４</div>
      <div class="question_right"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>４では<ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt><rb>用</rb><rt>よう</rt><rb>紙</rb><rt>し</rt></ruby>に<ruby><rb>何</rb><rt>なに</rt></ruby>もいんさつされていません。まず<ruby><rb>文</rb><rt>ぶん</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いてください。そのからその<ruby><rb>返</rb><rt>へん</rt><rb>事</rb><rt>じ</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いてａ・ｂ・ｃの<ruby><rb>中</rb><rt>なか</rt></ruby>から<br>もっともよいものを１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
    </div>

    <div class = "title1">
      <img src="{{ asset('image/2Q/example/q2s3q4example.png') }}" alt="Image"/>
    </div>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q2ListeningQ4SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">

                  <p><span class="sec3-ban">１<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
                      @if(!(app()->isProduction()))
        　　　        <button type="button" onclick="window.location='{{ url('/Q2ListeningQ4N2') }}'" style="vertical-align: right;">skip</button>
                      @endif
                  <span class="test-information">
                      @if(!(app()->isProduction()))
                      <i>{{$data[0]->getQid()}} || Class_Listening:{{$data[0]->getGroup1()}} || Class_Listening_Group:{{$data[0]->getGroup2()}} || anchor: {{$data[0]->getAnchor()}} || new: {{$data[0]->getNewQuestion()}} || correct answer: {{$data[0]->getCorrectAnswer()}}</i>
                      @endif
                  </span></p> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[0]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}a"> &nbsp; a.&nbsp;</label></span> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[0]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}b"> &nbsp; b.&nbsp;</label></span>
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[0]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}c"> &nbsp; c.&nbsp;</label></span>
                  <br>

                  <p><span class="sec3-ban">２<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
                  <span class="test-information">
                      @if(!(app()->isProduction()))
                      <i>{{$data[1]->getQid()}} || Class_Listening:{{$data[1]->getGroup1()}} || Class_Listening_Group:{{$data[1]->getGroup2()}} || anchor: {{$data[1]->getAnchor()}} || new: {{$data[1]->getNewQuestion()}} || correct answer: {{$data[1]->getCorrectAnswer()}}</i>
                      @endif
                  </span></p> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[1]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}a"> &nbsp; a.&nbsp;</label></span> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[1]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}b"> &nbsp; b.&nbsp;</label></span>
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[1]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}c"> &nbsp; c.&nbsp;</label></span>
                  <br>

                  <p><span class="sec3-ban">３<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
                  <span class="test-information">
                      @if(!(app()->isProduction()))
                      <i>{{$data[2]->getQid()}} || Class_Listening:{{$data[2]->getGroup1()}} || Class_Listening_Group:{{$data[2]->getGroup2()}} || anchor: {{$data[2]->getAnchor()}} || new: {{$data[2]->getNewQuestion()}} || correct answer: {{$data[2]->getCorrectAnswer()}}</i>
                      @endif
                  </span></p> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[2]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}a"> &nbsp; a.&nbsp;</label></span> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[2]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}b"> &nbsp; b.&nbsp;</label></span>
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[2]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}c"> &nbsp; c.&nbsp;</label></span>
                  <br>

                  <p><span class="sec3-ban">４<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
                  <span class="test-information">
                      @if(!(app()->isProduction()))
                      <i>{{$data[3]->getQid()}} || Class_Listening:{{$data[3]->getGroup1()}} || Class_Listening_Group:{{$data[3]->getGroup2()}} || anchor: {{$data[3]->getAnchor()}} || new: {{$data[3]->getNewQuestion()}} || correct answer: {{$data[3]->getCorrectAnswer()}}</i>
                      @endif
                  </span></p> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a"> &nbsp; a.&nbsp;</label></span> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b"> &nbsp; b.&nbsp;</label></span>
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c"> &nbsp; c.&nbsp;</label></span>
                  <br>

                  <p><span class="sec3-ban">５<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
                  <span class="test-information">
                      @if(!(app()->isProduction()))
                      <i>{{$data[4]->getQid()}} || Class_Listening:{{$data[4]->getGroup1()}} || Class_Listening_Group:{{$data[4]->getGroup2()}} || anchor: {{$data[4]->getAnchor()}} || new: {{$data[4]->getNewQuestion()}} || correct answer: {{$data[4]->getCorrectAnswer()}}</i>
                      @endif
                  </span></p> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[4]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}a"> &nbsp; a.&nbsp;</label></span> 
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[4]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}b"> &nbsp; b.&nbsp;</label></span>
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[4]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}c"> &nbsp; c.&nbsp;</label></span>

            </div>
          </div>
        </form> 
      </div>
    </div>
  </body>
</html>