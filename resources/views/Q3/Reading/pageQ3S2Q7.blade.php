@inject('Q4S2Q6', 'App\Http\Controllers\Q4\Reading\Q4S2Q6Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q3S2Q7</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script type="text/javascript" src="{{ URL::asset('js/animatescroll.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
 </head>
 <body>
 <script>
        function myFunction(id) {
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{ url('/saveChoiceRequestQ3S2Q7') }}",
//url:'/nat-test/saveChoiceRequestQ3S2Q7',
//url:'/saveChoiceRequestQ3S2Q7',
                data:{name:id.name, answer:id.value},
                success:function(data)
                {
                  //alert(data.success);
                }
            });
        }
        function scrollDown(clicked_id)
        {
          $('html').animate({scrollTop: $(window).scrollTop() + $(window).height()}, '500');    
        }

        function scrollUp(clicked_id)
        {
          $('html').animate({ scrollTop: 0 }, '500');
        }

        function submitFunction(){
          document.getElementById("submitButton").disabled = true;
          document.forms['yourForm'].submit();
        }
        
        $( window ).on( "load", function() {
          let scrollHeight = Math.max(
            document.body.scrollHeight, document.documentElement.scrollHeight,
            document.body.offsetHeight, document.documentElement.offsetHeight,
            document.body.clientHeight, document.documentElement.clientHeight
          );
                       console.log(scrollHeight);
          if (scrollHeight >1080)
          {
            // console.log("haha");
            console.log(document.getElementById("triangle-arrow-down"));
            document.getElementById("triangle-arrow-down").style.display  = "block";
            document.getElementById("triangle-arrow-up").style.display = "block";
            console.log(document.getElementById("triangle-arrow-down"));
          }
        });

// Set the date we're counting down to
@if(app()->isProduction())
var countDownDate = new Date().getTime() + 70*60000;
@else
var countDownDate = new Date().getTime() + 100*60000;
@endif
if (localStorage.getItem("timeCountDownQ3S2") !== null)
  var countDownDate = localStorage['timeCountDownQ3S2'];
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
    document.forms['yourForm'].submit();
    clearInterval(x);
  }
}, 1000);
    </script>

 <div class="container-fluid">
 <br />
 <div  class = "time-border">  <h4>あと<label class = "time-style" id="japaneseSentence">　</label>ふん</h4></div>
  <div class="question-title-width">
    <div class="left-side-problem-number" >
        <div class = "title1">  <h1>問題７</h1></div>
    </div>
    <div class="right-side-title">
        <div>  <h2>{!! $questionData[0]->getTitle() !!}</h2></div>
    </div>
   </div>
   <br />
  <div class = "Q5S2Q6-style">
    <div class = "Q5S2Q6-left-side">
      @foreach($questionData[0]->getIllustration() as $image)
      <div class = "title1">          <img src="{{url('/image/3Q/reading/Q3S2Q7/'.$image)}}" style="max-width:900px"alt="Image"/></div>
      @endforeach
    </div>
    <div class = "Q5S2Q6-right-side">

    @if($questionData[0]->getExplanationText() != "ZZZ")
   <div class = "explanation-text-style"> {!! $questionData[0]->getExplanationText() !!}</div>
    @endif
    <br />
    <br />

  <form method="post" action="{{ url('/Q3ReadingQ7SubmitData')}}" id="yourForm" name="yourForm">
  {{ csrf_field() }}

  <div class="container Q5S2Q6-box">
      @foreach($questionData as $question)

      <div class = "test">
      <p><span><strong>
        <div class="column-S2Q6" >
          <div style="border: 1px solid ">  {{$question->getQuestionId()}}</div>
        </div>
        <div class="column1-S2Q6">
          <div>  {!! $question->getQuestion() !!}</div>
        </div>
        <br />
      </strong></span>  </p>      
        <span class = "q5s2q6-question-style"><input type="radio" name="{{$question->getQuestionId()}}" id = "{{$question->getQuestionId()}}a" value="a" {{ $Q4S2Q6->checkValue($question->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$question->getQuestionId()}}a">&nbsp a. {!! $question->getChoiceA() !!}</label></span>
        <span class = "q5s2q6-question-style"><input type="radio" name="{{$question->getQuestionId()}}" id = "{{$question->getQuestionId()}}b" value="b" {{ $Q4S2Q6->checkValue($question->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$question->getQuestionId()}}b">&nbsp b. {!! $question->getChoiceB() !!}</label></span>
        <span class = "q5s2q6-question-style"><input type="radio" name="{{$question->getQuestionId()}}" id = "{{$question->getQuestionId()}}c" value="c" {{ $Q4S2Q6->checkValue($question->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$question->getQuestionId()}}c">&nbsp c. {!! $question->getChoiceC() !!}</label></span>
        <span class = "q5s2q6-question-style"><input type="radio" name="{{$question->getQuestionId()}}" id = "{{$question->getQuestionId()}}d" value="d" {{ $Q4S2Q6->checkValue($question->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$question->getQuestionId()}}d">&nbsp d. {!! $question->getChoiceD() !!}</label></span><br>
      </div>
      <div class = "test-information-q5s2q3">
          @if(!(app()->isProduction()))
          <i>id: {{$question->getDatabaseQuestionId()}}      |||||    correct answer: {{$question->getCorrectChoice()}}</i>
          @endif
      </div>
      @endforeach

      <a id = "triangle-arrow-down" class = "triangle-arrow-down" onclick = " scrollDown(this)"></a>
      <a id = "triangle-arrow-up" class = "triangle-arrow-up" onclick = " scrollUp(this)"></a>

      </div>
    </div>

        </div>

        <div class="submit-button-last-page">
        <label class = "end-of-part-label Q4S2Q6-label">これで　だいにぶんやの　しけんは　おわりです</label>

          <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary last-page-button-Q5S2Q6" value="だいさんぶんやへ" onclick="submitFunction();"/>
        </div>
</div>
  </form>

 </body>
</html>