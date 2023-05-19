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
		    var ban8 = document.getElementById("ban8");
        ban8.addEventListener("ended", function() { var audio8 = document.getElementById("audio8"); audio8.play(); });
		    var audio8 = document.getElementById("audio8");
        audio8.addEventListener("ended", function() { window.document.yourForm.submit(); });
      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          url:'/nat-test/saveChoiceRequestQ4S3Q1N8',
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
    <audio autoplay id="ban8">
      <source src="{{ url('/audio/4Q/'.$data[7]->getBanFile()) }}">
	  </audio>
    <audio id="audio8" preload="none">
      <source src="{{ url('/audio/4Q/'.$data[7]->getListening()) }}">
	  </audio>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q4ListeningQ1SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class = "test">
              <p><span class="sec3-ban">８ばん</span>
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.document.yourForm.submit();" style="vertical-align: right;">SKIP</button>
@endif
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[7]->getQid()}} || Class_Listening: {{$data[7]->getGroup3()}} || Class_Relationship: {{$data[7]->getGroup2()}} || Class_Place:{{$data[7]->getGroup1()}} || anchor:{{$data[7]->getAnchor()}} || correct answer: {{$data[7]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              @if ($data[7]->getPattern() == '2')
              <!-- ILLUSTRATION ONLY -->
              <span class="sec3-1-image">
                <img src="{{url('/image/4Q/listening/'.$data[7]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-1-choice">
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}a">&nbsp a. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}b">&nbsp b. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}c">&nbsp c. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}d">&nbsp d. </label></span>
              </span>
              @elseif ($data[7]->getPattern() == '3')
              <!-- CHOICE AND ILLUSTRATION -->
              <span class="sec3-1-image">
                <img src="{{url('/image/4Q/listening/'.$data[7]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              @if(mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[7]->getChoiceA()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[7]->getChoiceB()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[7]->getChoiceC()))))))) < 8 
              && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $data[7]->getChoiceD()))))))) < 8)
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}a">&nbsp a. {!!$data[7]->getChoiceA()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}b">&nbsp b. {!!$data[7]->getChoiceB()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}c">&nbsp c. {!!$data[7]->getChoiceC()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}d">&nbsp d. {!!$data[7]->getChoiceD()!!}</label></span>
              @else
              <div class="sec3-c2-medium2">
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}a" value="a" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}a">&nbsp a. {!!$data[7]->getChoiceA()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}b" value="b" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}b">&nbsp b. {!!$data[7]->getChoiceB()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}c" value="c" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}c">&nbsp c. {!!$data[7]->getChoiceC()!!}</label></span>
              <span class="sec3-c2-long-answer"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}d" value="d" {{ $Q4S3Q1->checkValue($data[7]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}d">&nbsp d. {!!$data[7]->getChoiceD()!!}</label></span>
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