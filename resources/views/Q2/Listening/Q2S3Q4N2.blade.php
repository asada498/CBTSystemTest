@inject('Q2S3Q4', 'App\Http\Controllers\Q2\Listening\Q2S3Q4N2Controller')

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
        var ban6 = document.getElementById("ban6");
        ban6.addEventListener("ended", function() { var audio6 = document.getElementById("audio6"); audio6.play(); });
        var audio6 = document.getElementById("audio6");
        audio6.addEventListener("ended", function() { var ban7 = document.getElementById("ban7"); ban7.play(); });
        var ban7 = document.getElementById("ban7");
        ban7.addEventListener("ended", function() { var audio7 = document.getElementById("audio7"); audio7.play(); });
        var audio7 = document.getElementById("audio7");
        audio7.addEventListener("ended", function() { var ban8 = document.getElementById("ban8"); ban8.play(); });
        var ban8 = document.getElementById("ban8");
        ban8.addEventListener("ended", function() { var audio8 = document.getElementById("audio8"); audio8.play(); });
        var audio8 = document.getElementById("audio8");
        audio8.addEventListener("ended", function() { var ban9 = document.getElementById("ban9"); ban9.play(); });
        var ban9 = document.getElementById("ban9");
        ban9.addEventListener("ended", function() { var audio9 = document.getElementById("audio9"); audio9.play(); });
        var audio9 = document.getElementById("audio9");
        audio9.addEventListener("ended", function() { var ban10 = document.getElementById("ban10"); ban10.play(); });
        var ban10 = document.getElementById("ban10");
        ban10.addEventListener("ended", function() { var audio10 = document.getElementById("audio10"); audio10.play(); });
        var audio10 = document.getElementById("audio10");
        audio10.addEventListener("ended", function() { var ban11 = document.getElementById("ban11"); ban11.play(); });
        var ban11 = document.getElementById("ban11");
        ban11.addEventListener("ended", function() { var audio11 = document.getElementById("audio11"); audio11.play(); });
        var audio11 = document.getElementById("audio11");
        audio11.addEventListener("ended", function() { var ban12 = document.getElementById("ban12"); ban12.play(); });
        var ban12 = document.getElementById("ban12");
        ban12.addEventListener("ended", function() { var audio12 = document.getElementById("audio12"); audio12.play(); });
        var audio12 = document.getElementById("audio12");
        audio12.addEventListener("ended", function() { window.document.yourForm.submit(); });
      }
      function myFunction(id) { 
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
          url:"{{ url('/saveChoiceRequestQ2S3Q4N2') }}",
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
      <source src="{{ url('/audio/2Q/'.$data[5]->getBanFile()) }}">
	  </audio>
    <audio id="audio6" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[5]->getListening()) }}">
	  </audio>
    <audio id="ban7" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[6]->getBanFile()) }}">
	  </audio>
    <audio id="audio7" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[6]->getListening()) }}">
	  </audio>
    <audio id="ban8" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[7]->getBanFile()) }}">
	  </audio>
    <audio id="audio8" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[7]->getListening()) }}">
	  </audio>
    <audio id="ban9" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[8]->getBanFile()) }}">
	  </audio>
    <audio id="audio9" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[8]->getListening()) }}">
	  </audio>
    <audio id="ban10" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[9]->getBanFile()) }}">
	  </audio>
    <audio id="audio10" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[9]->getListening()) }}">
	  </audio>
    <audio id="ban11" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[10]->getBanFile()) }}">
	  </audio>
    <audio id="audio11" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[10]->getListening()) }}">
	  </audio>
    <audio id="ban12" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[11]->getBanFile()) }}">
	  </audio>
    <audio id="audio12" preload="none">
      <source src="{{ url('/audio/2Q/'.$data[11]->getListening()) }}">
	  </audio>

    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q2ListeningQ4SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">

            <p><span class="sec3-ban">６<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.document.yourForm.submit();" >skip</button>
@endif
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[5]->getQid()}} || Class_Listening:{{$data[5]->getGroup1()}} || Class_Listening_Group:{{$data[5]->getGroup2()}} || anchor: {{$data[5]->getAnchor()}} || new: {{$data[5]->getNewQuestion()}} || correct answer: {{$data[5]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c. </label></span>
          <br>

            <p><span class="sec3-ban">７<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[6]->getQid()}} || Class_Listening:{{$data[6]->getGroup1()}} || Class_Listening_Group:{{$data[6]->getGroup2()}} || anchor: {{$data[6]->getAnchor()}} || new: {{$data[6]->getNewQuestion()}} || correct answer: {{$data[6]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[6]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}a">&nbsp a. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[6]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}b">&nbsp b. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[6]->getNo()}}" id="{{$data[6]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[6]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[6]->getNo()}}c">&nbsp c. </label></span>
          <br>

          <p><span class="sec3-ban">８<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[7]->getQid()}} || Class_Listening:{{$data[7]->getGroup1()}} || Class_Listening_Group:{{$data[7]->getGroup2()}} || anchor: {{$data[7]->getAnchor()}} || new: {{$data[7]->getNewQuestion()}} || correct answer: {{$data[7]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[7]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}a">&nbsp a. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[7]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}b">&nbsp b. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[7]->getNo()}}" id="{{$data[7]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[7]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[7]->getNo()}}c">&nbsp c. </label></span>
          <br>

          <p><span class="sec3-ban">９<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[8]->getQid()}} || Class_Listening:{{$data[8]->getGroup1()}} || Class_Listening_Group:{{$data[8]->getGroup2()}} || anchor: {{$data[8]->getAnchor()}} || new: {{$data[8]->getNewQuestion()}} || correct answer: {{$data[8]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[8]->getNo()}}" id="{{$data[8]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[8]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[8]->getNo()}}a">&nbsp a. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[8]->getNo()}}" id="{{$data[8]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[8]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[8]->getNo()}}b">&nbsp b. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[8]->getNo()}}" id="{{$data[8]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[8]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[8]->getNo()}}c">&nbsp c. </label></span>
          <br>

          <p><span class="sec3-ban">１０<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[9]->getQid()}} || Class_Listening:{{$data[9]->getGroup1()}} || Class_Listening_Group:{{$data[9]->getGroup2()}} || anchor: {{$data[9]->getAnchor()}} || new: {{$data[9]->getNewQuestion()}} || correct answer: {{$data[9]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[9]->getNo()}}" id="{{$data[9]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[9]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[9]->getNo()}}a">&nbsp a. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[9]->getNo()}}" id="{{$data[9]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[9]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[9]->getNo()}}b">&nbsp b. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[9]->getNo()}}" id="{{$data[9]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[9]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[9]->getNo()}}c">&nbsp c. </label></span>
          <br>

          <p><span class="sec3-ban">１１<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[10]->getQid()}} || Class_Listening:{{$data[10]->getGroup1()}} || Class_Listening_Group:{{$data[10]->getGroup2()}} || anchor: {{$data[10]->getAnchor()}} || new: {{$data[10]->getNewQuestion()}} || correct answer: {{$data[10]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[10]->getNo()}}" id="{{$data[10]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[10]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[10]->getNo()}}a">&nbsp a. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[10]->getNo()}}" id="{{$data[10]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[10]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[10]->getNo()}}b">&nbsp b. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[10]->getNo()}}" id="{{$data[10]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[10]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[10]->getNo()}}c">&nbsp c. </label></span>
          <br>

          <p><span class="sec3-ban">１２<ruby><rb>番</rb><rt>ばん</rt></ruby></span>
            <span class="test-information">
                @if(!(app()->isProduction()))
                <i>{{$data[11]->getQid()}} || Class_Listening:{{$data[11]->getGroup1()}} || Class_Listening_Group:{{$data[11]->getGroup2()}} || anchor: {{$data[11]->getAnchor()}} || new: {{$data[11]->getNewQuestion()}} || correct answer: {{$data[11]->getCorrectAnswer()}}</i>
                @endif
            </span></p>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[11]->getNo()}}" id="{{$data[11]->getNo()}}a" value="a" {{ $Q2S3Q4->checkValue($data[11]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[11]->getNo()}}a">&nbsp a. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[11]->getNo()}}" id="{{$data[11]->getNo()}}b" value="b" {{ $Q2S3Q4->checkValue($data[11]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[11]->getNo()}}b">&nbsp b. </label></span>
            <span class="sec3-q4-c1-tight"><input type="radio" name="{{$data[11]->getNo()}}" id="{{$data[11]->getNo()}}c" value="c" {{ $Q2S3Q4->checkValue($data[11]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[11]->getNo()}}c">&nbsp c. </label></span>


            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
