@inject('Q3S3Q4', 'App\Http\Controllers\Q3\Listening\Q3S3Q4N2Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q3ListeningQ4</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
    <script>
      function init()
      {
        var ban2 = document.getElementById("ban2");
        ban2.addEventListener("ended", function() { var audio2 = document.getElementById("audio2"); audio2.play(); });
        var audio2 = document.getElementById("audio2");
        audio2.addEventListener("ended", function() { var ban3 = document.getElementById("ban3"); ban3.play(); });
        var ban3 = document.getElementById("ban3");
        ban3.addEventListener("ended", function() { var audio3 = document.getElementById("audio3"); audio3.play(); });
        var audio3 = document.getElementById("audio3");
        audio3.addEventListener("ended", function() { window.location.href = "{{ url('/Q3ListeningQ4N3') }}"; });
      }
      function myFunction(id) { 
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
//url:'/nat-test/saveChoiceRequestQ3S3Q4N2',
          url:"{{ url('/saveChoiceRequestQ3S3Q4N2') }}",
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
    <audio autoplay id="ban2">
      <source src="{{ url('/audio/3Q/'.$data[1]->getBanFile()) }}">
	  </audio>
    <audio id="audio2" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[1]->getListening()) }}">
	  </audio>
    <audio  id="ban3" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[2]->getBanFile()) }}">
	  </audio>
    <audio id="audio3" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[2]->getListening()) }}">
	  </audio>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q3ListeningQ4SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">
              <div class ="main-bar">
            <p><span class="sec3-ban">２ばん</span>
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.location='{{ url('/Q3ListeningQ4N3')}}'" >skip</button>
@endif
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[1]->getQid()}} || Class_Listening:{{$data[1]->getGroup1()}} || Class_Listening_Group:{{$data[1]->getGroup2()}} || new: {{$data[1]->getNewQuestion()}} || correct answer: {{$data[1]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <!-- ILLUSTRATION ONLY -->
            <span class="sec3-image">
              <img src="{{url('/image/3Q/listening/'.$data[1]->getIllustration())}}" style="max-height:450px; max-width:850px;" alt="Image"/>
            </span>
          </div>
          <div class= "side-bar">
            <span class="sec3-choice">
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}a" value="a" {{ $Q3S3Q4->checkValue($data[1]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}a">&nbsp a. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}b" value="b" {{ $Q3S3Q4->checkValue($data[1]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}b">&nbsp b. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[1]->getNo()}}" id="{{$data[1]->getNo()}}c" value="c" {{ $Q3S3Q4->checkValue($data[1]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[1]->getNo()}}c">&nbsp c. </label></span>
            </span>
          </div><br>
          <div class ="main-bar">
            <p><span class="sec3-ban">３ばん</span>
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[2]->getQid()}} || Class_Listening:{{$data[2]->getGroup1()}} || Class_Listening_Group:{{$data[2]->getGroup2()}} || new: {{$data[2]->getNewQuestion()}} || correct answer: {{$data[2]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <!-- ILLUSTRATION ONLY -->
            <span class="sec3-image">
              <img src="{{url('/image/3Q/listening/'.$data[2]->getIllustration())}}" style="max-height:450px; max-width:850px;" alt="Image"/>
            </span>
          </div>
          <div class= "side-bar">
            <span class="sec3-choice">
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}a" value="a" {{ $Q3S3Q4->checkValue($data[2]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}a">&nbsp a. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}b" value="b" {{ $Q3S3Q4->checkValue($data[2]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}b">&nbsp b. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[2]->getNo()}}" id="{{$data[2]->getNo()}}c" value="c" {{ $Q3S3Q4->checkValue($data[2]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[2]->getNo()}}c">&nbsp c. </label></span>
            </span>
          </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
