@inject('Q3S3Q1', 'App\Http\Controllers\Q4\Listening\Q4S3Q1N6Controller')

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
//url:'/nat-test/saveChoiceRequestQ3S3Q1N6',
//url:'/saveChoiceRequestQ3S3Q1N6',
          url:"{{ url('/saveChoiceRequestQ3S3Q1N6') }}",
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
      <source src="{{ url('/audio/3Q/'.$data[5]->getBanFile()) }}">
	  </audio>
    <audio id="audio6" preload="none">
      <source src="{{ url('/audio/3Q/'.$data[5]->getListening()) }}">
	  </audio>

    <div class="box">
      <div id="table_data">
        <form method="post" action="{{ url('/Q3ListeningQ1SubmitData')}}" name="yourForm">
          {{ csrf_field() }}
          <div class="container box">
            <div class = "test">
              <p><span class="sec3-ban">６ばん</span>
@if(!(app()->isProduction()))
      <button type="button" onclick="window.document.yourForm.submit();" >skip</button>
@endif
              <span class="test-information">
                  @if(!(app()->isProduction()))
                  <i>{{$data[5]->getQid()}} || Class_Listening: {{$data[5]->getGroup1()}} || Class_Relationship: {{$data[5]->getGroup2()}} || Class_Place:{{$data[5]->getGroup3()}} || anchor:{{$data[5]->getAnchor()}} || correct answer: {{$data[5]->getCorrectAnswer()}}</i>
                  @endif
              </span></p>
              @if ($data[5]->getPattern() == '2')
              <!-- ILLUSTRATION ONLY -->
              <span class="sec3-1-image">
                <img src="{{url('/image/3Q/listening/'.$data[5]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-1-choice">
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q3S3Q1->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q3S3Q1->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q3S3Q1->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c. </label></span>
                <span class="sec3-c1-medium"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}d" value="d" {{ $Q3S3Q1->checkValue($data[5]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}d">&nbsp d. </label></span>
              </span>
              @elseif ($data[5]->getPattern() == '3')
              <!-- CHOICE AND ILLUSTRATION -->
              <span class="sec3-1-image">
                <img src="{{url('/image/3Q/listening/'.$data[5]->getIllustration())}}" style="max-width:850px; max-height:450px" alt="Image"/>
              </span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}a" value="a" {{ $Q3S3Q1->checkValue($data[5]->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}a">&nbsp a. {!!$data[5]->getChoiceA()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}b" value="b" {{ $Q3S3Q1->checkValue($data[5]->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}b">&nbsp b. {!!$data[5]->getChoiceB()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}c" value="c" {{ $Q3S3Q1->checkValue($data[5]->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}c">&nbsp c. {!!$data[5]->getChoiceC()!!}</label></span>
              <span class="sec3-c1-medium2"><input type="radio" name="{{$data[5]->getNo()}}" id="{{$data[5]->getNo()}}d" value="d" {{ $Q3S3Q1->checkValue($data[5]->getNo(),"d") }} onclick=" myFunction(this)" /><label for="{{$data[5]->getNo()}}d">&nbsp d. {!!$data[5]->getChoiceD()!!}</label></span>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>