@inject('Q3S3Q1', 'App\Http\Controllers\Q3\Listening\Q3S3Q1N4Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q3ListeningQ1</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
    <script>
      function init()
      {
		    var ban4 = document.getElementById("ban4");
        ban4.addEventListener("ended", function() { var audio4 = document.getElementById("audio4"); audio4.play(); });
		    var audio4 = document.getElementById("audio4");
        audio4.addEventListener("ended", function() { var ban5 = document.getElementById("ban5"); ban5.play(); });
		    var ban5 = document.getElementById("ban5");
        ban5.addEventListener("ended", function() { var audio5 = document.getElementById("audio5"); audio5.play(); });
		    var audio5 = document.getElementById("audio5");
        audio5.addEventListener("ended", function() { window.location.href = "{{ url('/Q3ListeningQ1N6') }}"; });
      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
//url:'/nat-test/saveChoiceRequestQ3S3Q1N4',
//url:'/saveChoiceRequestQ3S3Q1N4',
          url:"{{ url('/saveChoiceRequestQ3S3Q1N4') }}",
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
    <audio autoplay id="ban4">
      <source src="{{ url('/audio/3Q/'.$data[3]->getBanFile()) }}">
	  </audio>
    <audio id="audio4" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[3]->getListening()) }}">
	  </audio>
    <audio id="ban5" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[4]->getBanFile()) }}">
	  </audio>
    <audio id="audio5" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[4]->getListening()) }}">
	  </audio>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q3ListeningQ1SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class = "test">
              <p><span class="sec3-ban">４ばん</span>
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.location='{{ url('/Q3ListeningQ1N6') }}'" >skip</button>
@endif
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[3]->getQid()}} || Class_Listening: {{$data[3]->getGroup1()}} || Class_Relationship: {{$data[3]->getGroup2()}} || Class_Place:{{$data[3]->getGroup3()}} || anchor:{{$data[3]->getAnchor()}} || correct answer: {{$data[3]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>

              @if ($data[3]->getPattern() == '2')
              <!-- ILLUSTRATION ONLY -->
              <span class="sec3-1-image">
                <img src="{{url('/image/3Q/listening/'.$data[3]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-1-choice">
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q3S3Q1->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q3S3Q1->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q3S3Q1->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}d" value="d" {{ $Q3S3Q1->checkValue($data[3]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}d">&nbsp d. </label></span>
              </span>
              @elseif ($data[3]->getPattern() == '3')
              <!-- CHOICE AND ILLUSTRATION -->
              <span class="sec3-1-image">
                <img src="{{url('/image/3Q/listening/'.$data[3]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q3S3Q1->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a. {!!$data[3]->getChoiceA()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q3S3Q1->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b. {!!$data[3]->getChoiceB()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q3S3Q1->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c. {!!$data[3]->getChoiceC()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}d" value="d" {{ $Q3S3Q1->checkValue($data[3]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}d">&nbsp d. {!!$data[3]->getChoiceD()!!}</label></span>
              @endif
              <p><span class="sec3-ban">５ばん</span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[4]->getQid()}} || Class_Listening: {{$data[4]->getGroup1()}} || Class_Relationship: {{$data[4]->getGroup2()}} || Class_Place:{{$data[4]->getGroup3()}} || anchor:{{$data[4]->getAnchor()}} || correct answer: {{$data[4]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              @if ($data[4]->getPattern() == '2')
              <!-- ILLUSTRATION ONLY -->
              <span class="sec3-1-image">
                <img src="{{url('/image/3Q/listening/'.$data[4]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-1-choice">
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}a" value="a" {{ $Q3S3Q1->checkValue($data[4]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}a">&nbsp a. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}b" value="b" {{ $Q3S3Q1->checkValue($data[4]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}b">&nbsp b. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}c" value="c" {{ $Q3S3Q1->checkValue($data[4]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}c">&nbsp c. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}d" value="d" {{ $Q3S3Q1->checkValue($data[4]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}d">&nbsp d. </label></span>
              </span>
              @elseif ($data[4]->getPattern() == '3')
              <!-- CHOICE AND ILLUSTRATION -->
              <span class="sec3-1-image">
                <img src="{{url('/image/3Q/listening/'.$data[4]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}a" value="a" {{ $Q3S3Q1->checkValue($data[4]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}a">&nbsp a. {!!$data[4]->getChoiceA()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}b" value="b" {{ $Q3S3Q1->checkValue($data[4]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}b">&nbsp b. {!!$data[4]->getChoiceB()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}c" value="c" {{ $Q3S3Q1->checkValue($data[4]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}c">&nbsp c. {!!$data[4]->getChoiceC()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}d" value="d" {{ $Q3S3Q1->checkValue($data[4]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}d">&nbsp d. {!!$data[4]->getChoiceD()!!}</label></span>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>