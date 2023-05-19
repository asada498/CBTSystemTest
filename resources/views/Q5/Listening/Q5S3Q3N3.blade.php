@inject('Q5S3Q3', 'App\Http\Controllers\Q5\Listening\Q5S3Q3N3Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q5ListeningQ3</title>
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
        audio5.addEventListener("ended", function() { window.document.yourForm.submit(); });
      }

      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          url:'/nat-test/saveChoiceRequestQ5S3Q3N3',
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
      <source src="{{ url('/audio/5Q/'.$data[3]->getBanFile()) }}">
	  </audio>
    <audio id="audio4" preload="none">
      <source src="{{ url('/audio/5Q/'.$data[3]->getListening()) }}">
	  </audio>
    <audio id="ban5" preload="none">
      <source src="{{ url('/audio/5Q/'.$data[4]->getBanFile()) }}">
	  </audio>
    <audio id="audio5" preload="none">
      <source src="{{ url('/audio/5Q/'.$data[4]->getListening()) }}">
	  </audio>
    
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q5ListeningQ3SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">
              <div class ="main-bar">
            <p><span class="sec3-ban">４ばん</span>
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.document.yourForm.submit();" >skip</button>
@endif
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[3]->getQid()}} || Class_Listening:{{$data[3]->getGroup1()}} || Class_Listening_Group:{{$data[3]->getGroup2()}} || new: {{$data[3]->getNewQuestion()}} || correct answer: {{$data[3]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <!-- ILLUSTRATION ONLY -->
            <span class="sec3-image">
              <img src="{{url('/image/'.$data[3]->getIllustration())}}" style="max-height:450px; max-width:850px;" alt="Image"/>
            </span>
          </div>
          <div class= "side-bar">
            <span class="sec3-choice">
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q5S3Q3->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q5S3Q3->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q5S3Q3->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c. </label></span>
            </span>
          </div><br>
          <div class ="main-bar">
            <p><span class="sec3-ban">５ばん</span>
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[4]->getQid()}} || Class_Listening:{{$data[4]->getGroup1()}} || Class_Listening_Group:{{$data[4]->getGroup2()}} || new: {{$data[4]->getNewQuestion()}} || correct answer: {{$data[4]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <!-- ILLUSTRATION ONLY -->
            <span class="sec3-image">
              <img src="{{url('/image/'.$data[4]->getIllustration())}}" style="max-height:450px; max-width:850px;" alt="Image"/>
            </span>
          </div>
          <div class= "side-bar">
            <span class="sec3-choice">
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}a" value="a" {{ $Q5S3Q3->checkValue($data[4]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}a">&nbsp a. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}b" value="b" {{ $Q5S3Q3->checkValue($data[4]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}b">&nbsp b. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}c" value="c" {{ $Q5S3Q3->checkValue($data[4]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}c">&nbsp c. </label></span>
            </span>
          </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>