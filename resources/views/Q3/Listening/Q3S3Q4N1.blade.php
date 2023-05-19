@inject('Q3S3Q4', 'App\Http\Controllers\Q3\Listening\Q3S3Q4N1Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q3ListeningQ3</title>
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
        audio1.addEventListener("ended", function() { window.location.href = "{{ url('/Q3ListeningQ4N2') }}"; });
      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
//url:'/nat-test/saveChoiceRequestQ3S3Q4N1',
//url:'/saveChoiceRequestQ3S3Q4N1',
          url:"{{ url('/saveChoiceRequestQ3S3Q4N1') }}",
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
	  <source src="{{ url('/audio/3Q/22-3/Track 22.mp3') }}">
    </audio>
    <audio id="ban1" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[0]->getBanFile()) }}">
	  </audio>
    <audio id="audio1" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[0]->getListening()) }}">
	  </audio>
    <br/>
    <div class="question">
      <div class="question_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>４</div>
      <div class="question_right"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>４では<ruby><rb>絵</rb><rt>え</rt></ruby>を<ruby><rb>見</rb><rt>み</rt></ruby>ながら<ruby><rb>質</rb><rt>しつ</rt><rb>問</rb><rt>もん</rt></ruby>を<ruby><rb>聞</rb><rt>き</rt></ruby>いてください。➡（やじるし）の<ruby><rb>人</rb><rt>ひと</rt></ruby>は<ruby><rb>何</rb><rt>なん</rt></ruby>と<ruby><rb>言</rb><rt>い</rt></ruby>いますか。ａ・ｂ・ｃの<ruby><rb>中</rb><rt>なか</rt></ruby>からもっともよいものを１つえらんでください。</div>
    </div>
    <div class = "title1">
      <img src="{{ asset('image/3Q/example/q3s3q4example.png') }}" alt="Image"/>
    </div>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q3ListeningQ4SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">
              <div class ="main-bar">
                  <p><span class="sec3-ban">１ばん</span>
@if(!(app()->isProduction()))
    　　　        <button type="button" onclick="window.location='{{ url('/Q3ListeningQ4N2') }}'" style="vertical-align: right;">skip</button>
@endif
                  <span class="test-information">
                      @if(!(app()->isProduction()))
                      <i>{{$data[0]->getQid()}} || Class_Listening:{{$data[0]->getGroup1()}} || Class_Listening_Group:{{$data[0]->getGroup2()}} || new: {{$data[0]->getNewQuestion()}} || correct answer: {{$data[0]->getCorrectAnswer()}}</i>
                      @endif
                  </span></p>
                  <!-- ILLUSTRATION ONLY -->
                  <span class="sec3-image">
                  <img src="{{url('/image/3Q/listening/'.$data[0]->getIllustration())}}" style="max-height:450px; max-width:850px;" alt="Image"/>
                  </span>
                </div>
                 <div class= "side-bar">
                  <span class="sec3-choice">
                    <span class="sec3-c1-medium2"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}a" value="a" {{ $Q3S3Q4->checkValue($data[0]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}a"> &nbsp; a.&nbsp;</label></span> 
                    <span class="sec3-c1-medium2"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}b" value="b" {{ $Q3S3Q4->checkValue($data[0]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}b"> &nbsp; b.&nbsp;</label></span>
                    <span class="sec3-c1-medium2"><input type="radio" name="{{$data[0]->getNo()}}" id="{{$data[0]->getNo()}}c" value="c" {{ $Q3S3Q4->checkValue($data[0]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[0]->getNo()}}c"> &nbsp; c.&nbsp;</label></span>
                   </span>
                  </div>
            </div>
          </div>
        </form> 
      </div>
    </div>
  </body>
</html>