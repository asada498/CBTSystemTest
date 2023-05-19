@inject('Q4S1Q2', 'App\Http\Controllers\Q4\Vocabulary\Q4S1Q2Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q4S1Q4</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
 </head>
 <body>
 <br />
 <div class="box">
  <div  class = "time-border">  <h4>あと<label class = "time-style" id="japaneseSentence">　</label> ふん</h4></div>
  <br />

  <div class = "title1">          <img src="{{ asset('image/4Q/example/q4p1q2example.png') }}" style="width:1700px" alt="Image"/></div>
    <script>
        function myFunction(id) {
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'/nat-test/saveChoiceRequestQ4S1Q2',
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
    var countDownDate = new Date().getTime() + 25*60000 ;
  @else
    var countDownDate = new Date().getTime() + 100*60000 ;
  @endif

  if (localStorage.getItem("timeCountDownQ4S1") !== null)
    var countDownDate = localStorage['timeCountDownQ4S1'];

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for hours, minutes and seconds
  var minutes = Math.floor((distance % (1000 * 60 * 60 * 60)) / (1000 * 60))+1;
    
  document.getElementById("japaneseSentence").innerHTML = minutes;
    
  // If the count down is over, write some text 
  if (distance < 1000) {
    $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'/nat-test/timeOutQ4S1',
                success:function(data)
                {
                  console.log("SUCCESS");
                  window.location = "/nat-test/Q4ReadingWelcome";
                }
            });
    clearInterval(x);
  }
}, 1000);
    </script>

  <form method="post" action="{{ url('/Q4VocabularyQ2SubmitData')}}" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
  @foreach($data as $post)
      <div class = "test">
      @if($post->getQuestionId() > 9) 
      <p class = 'question'><span><strong><span class = "question-border">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
      @else
      <p class = 'question'><span><strong><span class = "single-digit-question-border">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
      @endif
        <span class = "test-information">
            @if(!(app()->isProduction()))
            <i>Id:{{$post->getDatabaseQuestionId()}}      |||||    Vocabulary:{{$post->getGroup1()}}      |||||  Kanji_reading: {{$post->getGroup2()}}    |||||correct answer: {{$post->getCorrectChoice()}}</i>
            @endif
        </span></p>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q4S1Q2->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {{$post->getChoiceA()}}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q4S1Q2->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {{$post->getChoiceB()}}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q4S1Q2->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {{$post->getChoiceC()}}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q4S1Q2->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {{$post->getChoiceD()}}</label></span><br>
      </div>
      @endforeach
      <div class="submit-button">
      <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary extra-first-page-padding" value="つぎの　もんだいへ" onclick="submitFunction();"/>
      </div>
  </div>
  </form>
 </body>
</html>