@inject('Q3S3Q4', 'App\Http\Controllers\Q3\Listening\Q3S3Q4N3Controller')

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
        var ban4 = document.getElementById("ban4");
        ban4.addEventListener("ended", function() { var audio4 = document.getElementById("audio4"); audio4.play(); });
        var audio4 = document.getElementById("audio4");
        audio4.addEventListener("ended", function() { window.document.yourForm.submit(); });
      }

      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
//url:'/nat-test/saveChoiceRequestQ3S3Q4N3',
          url:"{{ url('/saveChoiceRequestQ3S3Q4N3') }}",
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
    
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q3ListeningQ4SubmitData')}}" name="yourForm">
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
              <img src="{{url('/image/3Q/listening/'.$data[3]->getIllustration())}}" style="max-height:450px; max-width:850px;" alt="Image"/>
            </span>
          </div>
          <div class= "side-bar">
            <span class="sec3-choice">
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}a" value="a" {{ $Q3S3Q4->checkValue($data[3]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}a">&nbsp a. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}b" value="b" {{ $Q3S3Q4->checkValue($data[3]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}b">&nbsp b. </label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[3]->getNo()}}" id="{{$data[3]->getNo()}}c" value="c" {{ $Q3S3Q4->checkValue($data[3]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[3]->getNo()}}c">&nbsp c. </label></span>
            </span>
          </div>

            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>