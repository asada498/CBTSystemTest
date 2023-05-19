@inject('Q3S3Q2', 'App\Http\Controllers\Q3\Listening\Q3S3Q2N5Controller')

<!DOCTYPE html>
<html>
  <head>
    <title>Q3ListeningQ2</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
    <script>
      function init()
      {
		    var ban5 = document.getElementById("ban5");
        ban5.addEventListener("ended", function() { var audio5 = document.getElementById("audio5"); audio5.play(); });
		    var audio5 = document.getElementById("audio5");
        audio5.addEventListener("ended", function() { var ban6 = document.getElementById("ban6"); ban6.play(); });
        var ban6 = document.getElementById("ban6");
        ban6.addEventListener("ended", function() { var audio6 = document.getElementById("audio6"); audio6.play(); });
        var audio6 = document.getElementById("audio6");
        audio6.addEventListener("ended", function() { window.document.yourForm.submit(); });
      }
      function myFunction(id) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'POST',
//url:'/nat-test/saveChoiceRequestQ3S3Q2N5',
//url:'/saveChoiceRequestQ3S3Q2N5',
          url:"{{ url('/saveChoiceRequestQ3S3Q2N5') }}",
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
    <audio autoplay id="ban5">
      <source src="{{ url('/audio/3Q/'.$data[4]->getBanFile()) }}">
	  </audio>
    <audio id="audio5" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[4]->getListening()) }}">
	  </audio>
    <audio id="ban6" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[5]->getBanFile()) }}">
	  </audio>
    <audio id="audio6" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[5]->getListening()) }}">
	  </audio>
    <br/>
    <br/>
    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q3ListeningQ2SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class="test">
              <p><span class="sec3-ban">５ばん</span>
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.document.yourForm.submit();" >skip</button>
@endif
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[4]->getQid()}} || Class_Listening: {{$data[4]->getGroup1()}} || Class_Relationship: {{$data[4]->getGroup2()}} || Class_Place:{{$data[4]->getGroup3()}} ||  anchor:{{$data[4]->getAnchor()}} || correct answer: {{$data[4]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              @if ($data[4]->getRows() == 2)
              <span class="sec3-c2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}a" value="a" {{ $Q3S3Q2->checkValue($data[4]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}a">&nbsp a. {!!$data[4]->getChoiceA()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}b" value="b" {{ $Q3S3Q2->checkValue($data[4]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}b">&nbsp b. {!!$data[4]->getChoiceB()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}c" value="c" {{ $Q3S3Q2->checkValue($data[4]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}c">&nbsp c. {!!$data[4]->getChoiceC()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}d" value="d" {{ $Q3S3Q2->checkValue($data[4]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}d">&nbsp d. {!!$data[4]->getChoiceD()!!}</label></span>
              @else
              <span class="sec3-c1"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}a" value="a" {{ $Q3S3Q2->checkValue($data[4]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}a">&nbsp a. {!!$data[4]->getChoiceA()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}b" value="b" {{ $Q3S3Q2->checkValue($data[4]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}b">&nbsp b. {!!$data[4]->getChoiceB()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}c" value="c" {{ $Q3S3Q2->checkValue($data[4]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}c">&nbsp c. {!!$data[4]->getChoiceC()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[4]->getNo()}}" id="{{$data[4]->getNo()}}d" value="d" {{ $Q3S3Q2->checkValue($data[4]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[4]->getNo()}}d">&nbsp d. {!!$data[4]->getChoiceD()!!}</label></span>
              @endif
              <br><br>
              <p><span class="sec3-ban">６ばん</span>
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[5]->getQid()}} || Class_Listening: {{$data[5]->getGroup1()}} || Class_Relationship: {{$data[5]->getGroup2()}} || Class_Place:{{$data[5]->getGroup3()}} ||  anchor:{{$data[5]->getAnchor()}} || correct answer: {{$data[5]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              <!-- CHOICE ONLY -->
              @if ($data[5]->getRows() == 2)
              <span class="sec3-c2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q3S3Q2->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a. {!!$data[5]->getChoiceA()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q3S3Q2->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b. {!!$data[5]->getChoiceB()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q3S3Q2->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c. {!!$data[5]->getChoiceC()!!}</label></span>
              <span class="sec3-c2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}d" value="d" {{ $Q3S3Q2->checkValue($data[5]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}d">&nbsp d. {!!$data[5]->getChoiceD()!!}</label></span>
              @else
              <span class="sec3-c1"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q3S3Q2->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a. {!!$data[5]->getChoiceA()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q3S3Q2->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b. {!!$data[5]->getChoiceB()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q3S3Q2->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c. {!!$data[5]->getChoiceC()!!}</label></span>
              <span class="sec3-c1"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}d" value="d" {{ $Q3S3Q2->checkValue($data[5]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}d">&nbsp d. {!!$data[5]->getChoiceD()!!}</label></span>
              @endif
              <br><br>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>