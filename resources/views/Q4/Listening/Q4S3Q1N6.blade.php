@inject('Q4S3Q1', 'App\Http\Controllers\Q4\Listening\Q4S3Q1N6Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q4ListeningQ1</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
    <script>
      function init()
      {
		    var ban6 = document.getElementById("ban6");
        ban6.addEventListener("ended", function() { var audio6 = document.getElementById("audio6"); audio6.play(); });
		    var audio6 = document.getElementById("audio6");
        audio6.addEventListener("ended", function() { var ban7 = document.getElementById("ban7"); ban7.play(); });
		    var ban7 = document.getElementById("ban7");
        ban7.addEventListener("ended", function() { var audio7 = document.getElementById("audio7"); audio7.play(); });
		    var audio7 = document.getElementById("audio7");
        audio7.addEventListener("ended", function() { window.location.href = "{{ url('/Q4ListeningQ1N8') }}"; });
      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          url:'/nat-test/saveChoiceRequestQ4S3Q1N6',
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
    <audio autoplay id="ban6">
      <source src="{{ url('/audio/4Q/'.$data[5]->getBanFile()) }}">
	  </audio>
    <audio id="audio6" preload="none">
      <source src="{{ url('/audio/4Q/'.$data[5]->getListening()) }}">
	  </audio>
    <audio id="silence6" preload="none">
      <source src="{{ url('/audio/silence/silence'.$data[5]->getSilence().'.mp3') }}">
	  </audio>
    <audio id="ban7" preload="none">
      <source src="{{ url('/audio/4Q/'.$data[6]->getBanFile()) }}">
	  </audio>
    <audio id="audio7" preload="none">
      <source src="{{ url('/audio/4Q/'.$data[6]->getListening()) }}">
	  </audio>
    <audio id="silence7" preload="none">
      <source src="{{ url('/audio/silence/silence'.$data[6]->getSilence().'.mp3') }}">
	  </audio>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q4ListeningQ1SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class = "test">
              <p><span class="sec3-ban">６ばん</span>
@if(!(app()->isProduction()))
      <button type="button" onclick="window.location='{{ url('/Q4ListeningQ1N8') }}'" >skip</button>
@endif
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[5]->getQid()}} || Class_Listening: {{$data[5]->getGroup3()}} || Class_Relationship: {{$data[5]->getGroup2()}} || Class_Place:{{$data[5]->getGroup1()}} || anchor:{{$data[5]->getAnchor()}} || correct answer: {{$data[5]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              @if ($data[5]->getPattern() == '2')
              <!-- ILLUSTRATION ONLY -->
              <span class="sec3-1-image">
                <img src="{{url('/image/4Q/listening/'.$data[5]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-1-choice">
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}d">&nbsp d. </label></span>
              </span>
              @elseif ($data[5]->getPattern() == '3')
              <!-- CHOICE AND ILLUSTRATION -->
              <span class="sec3-1-image">
                <img src="{{url('/image/4Q/listening/'.$data[5]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              @if(mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[5]->getChoiceA()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[5]->getChoiceB()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[5]->getChoiceC()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[5]->getChoiceD()))))))) < 8)
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a. {!!$data[5]->getChoiceA()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b. {!!$data[5]->getChoiceB()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c. {!!$data[5]->getChoiceC()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}d">&nbsp d. {!!$data[5]->getChoiceD()!!}</label></span>
              @else
              <div class="sec3-c2-medium2">
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a. {!!$data[5]->getChoiceA()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b. {!!$data[5]->getChoiceB()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c. {!!$data[5]->getChoiceC()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[5]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}d">&nbsp d. {!!$data[5]->getChoiceD()!!}</label></span>
              </div>
              @endif
              @endif
              <p><span class="sec3-ban">７ばん</span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[6]->getQid()}} || Class_Listening: {{$data[6]->getGroup3()}} || Class_Relationship: {{$data[6]->getGroup2()}} || Class_Place:{{$data[6]->getGroup1()}} || anchor:{{$data[6]->getAnchor()}} || correct answer: {{$data[6]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              @if ($data[6]->getPattern() == '2')
              <!-- ILLUSTRATION ONLY -->
              <span class="sec3-1-image">
                <img src="{{url('/image/4Q/listening/'.$data[6]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-1-choice">
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}a">&nbsp a. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}b">&nbsp b. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}c">&nbsp c. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}d">&nbsp d. </label></span>
              </span>
              @elseif ($data[6]->getPattern() == '3')
              <!-- CHOICE AND ILLUSTRATION -->
              <span class="sec3-1-image">
                <img src="{{url('/image/4Q/listening/'.$data[6]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              @if(mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[6]->getChoiceA()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[6]->getChoiceB()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[6]->getChoiceC()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[6]->getChoiceD()))))))) < 8)
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}a">&nbsp a. {!!$data[6]->getChoiceA()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}b">&nbsp b. {!!$data[6]->getChoiceB()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}c">&nbsp c. {!!$data[6]->getChoiceC()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}d">&nbsp d. {!!$data[6]->getChoiceD()!!}</label></span>
              @else
              <div class="sec3-c2-medium2">
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}a">&nbsp a. {!!$data[6]->getChoiceA()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}b">&nbsp b. {!!$data[6]->getChoiceB()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}c">&nbsp c. {!!$data[6]->getChoiceC()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[6]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}d">&nbsp d. {!!$data[6]->getChoiceD()!!}</label></span>
              </div>
              @endif
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>