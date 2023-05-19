@inject('Q2S1Q14', 'App\Http\Controllers\Q2\Vocabulary\Q2S1Q14Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q2S1Q14</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
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
                  url:"{{ url('saveChoiceRequestQ2S1Q14') }}",
                  data:{name:id.name, answer:id.value},
                  success:function(data)
                  {
                    //alert(data.success);
                  }
              });
          }
          function submitFunction(){
            document.getElementById("submitButton").disabled = true;
            document.forms['yourForm'].submit();
          }
          // Set the date we're counting down to
          @if(app()->isProduction())
          var countDownDate = new Date().getTime() + 105*60*1000;
          @else
          var countDownDate = new Date().getTime() + 200*60*1000;
          @endif
          if (localStorage.getItem("timeCountDownQ2S1") !== null)
            var countDownDate = localStorage['timeCountDownQ2S1'];
          // Update the count down every 1 second
          var x = setInterval(function() 
          {

            // Get today's date and time
            var now = new Date().getTime();
              
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
              
            // Time calculations for hours, minutes and seconds
            var minutes = Math.floor((distance % (1000 * 60 * 60 * 60)) / (1000 * 60))+ 1;
              
            document.getElementById("japaneseSentence").innerHTML = minutes;
              
            if (distance < 1000) {
              document.forms['yourForm'].submit();
              clearInterval(x);
            }
          }, 1000);
      </script>
      <div class="box">
        <br />
        <div  class = "time-border">  <h4>あと<label class = "time-style" id="japaneseSentence">　</label>ふん</h4></div>
        <br>
        <div class="question">
          <div class="question2_left_wide">問題１４</div>
          <div class="question2_right">{!! $questionData[0]->getTitle()!!}</div>
        </div>
        <br>
        <form method="post" action="{{ url('/Q2VocabularyQ14SubmitData')}}" id="yourForm" name="yourForm">
          {{ csrf_field() }}
          
          <div class = "Q5S2Q6-style">
            <div class = "Q2S1Q14-left-side">
              @foreach($questionData[0]->getIllustration() as $image)
                <!-- <div class = "title1">          <img src="{{url('/image/2Q/vocabulary/Q2S1Q14/'.$image)}}" style="width:100%" alt="Image"/></div> -->
                <div class = "title1">          <img src="{{url('/image/2Q/vocabulary/Q2S1Q14/'.$image)}}" style="width:100%" alt="Image"/></div>
              @endforeach
            </div>
            <div class = "Q2S1Q14-right-side">
              @if($questionData[0]->getExplanationText() != "ZZZ")
                <div class = "explanation-text-style"> {!! $questionData[0]->getExplanationText() !!}</div>
              @endif
              <br>
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
                    <span class = "q5s2q6-question-style"><input type="radio" name="{{$question->getQuestionId()}}" id = "{{$question->getQuestionId()}}a" value="a" {{ $Q2S1Q14->checkValue($question->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$question->getQuestionId()}}a">&nbsp a. {!! $question->getChoiceA() !!}</label></span>
                    <span class = "q5s2q6-question-style"><input type="radio" name="{{$question->getQuestionId()}}" id = "{{$question->getQuestionId()}}b" value="b" {{ $Q2S1Q14->checkValue($question->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$question->getQuestionId()}}b">&nbsp b. {!! $question->getChoiceB() !!}</label></span>
                    <span class = "q5s2q6-question-style"><input type="radio" name="{{$question->getQuestionId()}}" id = "{{$question->getQuestionId()}}c" value="c" {{ $Q2S1Q14->checkValue($question->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$question->getQuestionId()}}c">&nbsp c. {!! $question->getChoiceC() !!}</label></span>
                    <span class = "q5s2q6-question-style"><input type="radio" name="{{$question->getQuestionId()}}" id = "{{$question->getQuestionId()}}d" value="d" {{ $Q2S1Q14->checkValue($question->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$question->getQuestionId()}}d">&nbsp d. {!! $question->getChoiceD() !!}</label></span><br>
                  </div>
                  <div class = "test-information-q5s2q3">
                    @if(!(app()->isProduction()))
                      <i>id: {{$question->getDatabaseQuestionId()}}      |||||    correct answer: {{$question->getCorrectChoice()}}</i>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="submit-button-last-page">
            <label class = "end-of-part-label Q4S2Q6-label">これで　だいいちぶんやの　しけんは　おわりです</label>
            <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary last-page-button-Q5S2Q6" value="だいにぶんやへ" onclick="submitFunction();"/>
          </div>
        </form>
      </div>
 </body> 
</html>

