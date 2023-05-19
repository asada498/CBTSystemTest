@inject('Q4S2Q3', 'App\Http\Controllers\Q4\Reading\Q4S2Q3Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q4S2Q3</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
 </head>
 <body>
 <div class="box">
 <br />
 <div  class = "time-border">  <h4>あと<label class = "time-style" id="japaneseSentence">　</label>ふん</h4></div>
  <div class = "title1">          <img src="{{ asset('image/4Q/example/q4p2q3example.png') }}" alt="Image"/></div>
    <script>
        function myFunction(id) {
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'/nat-test/saveChoiceRequestQ4S2Q3',
                data:{name:id.name, answer:id.value},
                success:function(data)
                {
                  // alert(data.success);
                }
            });
        }
        function submitFunction(){
          document.getElementById("submitButton").disabled = true;
          document.forms['yourForm'].submit();
        }
// Set the date we're counting down to
@if(app()->isProduction())
var countDownDate = new Date().getTime() + 55*60000;
@else
var countDownDate = new Date().getTime() + 100*60000;
@endif
if (localStorage.getItem("timeCountDownQ4S2") !== null)
  var countDownDate = localStorage['timeCountDownQ4S2'];
// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for hours, minutes and seconds
  var minutes = Math.floor((distance % (1000 * 60 * 60 * 60)) / (1000 * 60))+ 1;
    
  document.getElementById("japaneseSentence").innerHTML = minutes;
    
  // If the count down is over, write some text 
  if (distance < 1000) {
    $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'/nat-test/timeOutQ4S2',
                success:function(data)
                {
                  console.log("SUCCESS");
                  window.location = "/nat-test/Q4S3Start";
                }
            });
    // document.forms['yourForm'].submit();
    // window.location = "/EoP1";

    clearInterval(x);
  }
}, 1000);
    </script>
  <form method="post" action="{{ url('/Q4ReadingQ3SubmitData')}}" id="yourForm" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
      <p class = "text-question-4-s3">{!! $questionText !!}</p>
      @foreach($questionData as $post)
      @if(strlen($post->getChoiceA()) < 30 && strlen($post->getChoiceB()) < 30 && strlen($post->getChoiceB()) < 30 && strlen($post->getChoiceB()) < 30)
      <div class = "test">
      <span><strong><span style="border: 1px solid ">{{$post->getQuestion()}}</span></strong></span>        
        <span class = "c1"><input type="radio" name="{{$post->getQuestion()}}" id = "{{$post->getQuestion()}}a" value="a" {{ $Q4S2Q3->checkValue($post->getQuestion(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestion()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestion()}}" id = "{{$post->getQuestion()}}b" value="b" {{ $Q4S2Q3->checkValue($post->getQuestion(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestion()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestion()}}" id = "{{$post->getQuestion()}}c" value="c" {{ $Q4S2Q3->checkValue($post->getQuestion(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestion()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestion()}}" id = "{{$post->getQuestion()}}d" value="d" {{ $Q4S2Q3->checkValue($post->getQuestion(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestion()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
      </div>
      <div class = "test-information-q5s2q3">
          @if(!(app()->isProduction()))
          <i>Id:{{$post->getDatabaseQuestionId()}}      |||||    Grammar:{{$post->getGrammarClass()}}      |||||  correct answer: {{$post->getCorrectChoice()}}</i>
          @endif
      </div>
      @else
      <div class = "test">
      <span><strong><span style="border: 1px solid ">{{$post->getQuestion()}}</span></strong></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestion()}}" id = "{{$post->getQuestion()}}a" value="a" {{ $Q4S2Q3->checkValue($post->getQuestion(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestion()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestion()}}" id = "{{$post->getQuestion()}}b" value="b" {{ $Q4S2Q3->checkValue($post->getQuestion(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestion()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
        <span class = "new-line-padding c2"><input type="radio" name="{{$post->getQuestion()}}" id = "{{$post->getQuestion()}}c" value="c" {{ $Q4S2Q3->checkValue($post->getQuestion(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestion()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
        <span class = "new-line-padding c2"><input type="radio" name="{{$post->getQuestion()}}" id = "{{$post->getQuestion()}}d" value="d" {{ $Q4S2Q3->checkValue($post->getQuestion(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestion()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
      </div>
      <div class = "test-information-q5s2q3">
          @if(!(app()->isProduction()))
          <i>Id:{{$post->getDatabaseQuestionId()}}      |||||    Grammar:{{$post->getGrammarClass()}}      ||||| correct answer: {{$post->getCorrectChoice()}}</i>
          @endif
      </div>

      @endif
      @endforeach
      
      <div class="submit-button">
      <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary" value="つぎの　もんだいへ" onclick="submitFunction();"/>
      </div>
  </div>
  </div>

  </form>

 </body>
</html>