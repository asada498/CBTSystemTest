@inject('Q1S1Q10', 'App\Http\Controllers\Q1\Vocabulary\Q1S1Q10Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q1S1Q10</title>
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
      <br>
      <div class="question">
          <div class="question2_left_wide">問題１０</div>
          <div class="question2_right">次の文章を読んで、後の問いに答えてください。答えはそれぞれａ・ｂ・ｃ・ｄの中から一番いい物を１つ選んでください。</div>
        </div>
      
      <script>
          function myFunction(id) {
              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  type:'POST',
                  url:"{{ url('saveChoiceRequestQ1S1Q10') }}",
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
          var countDownDate = new Date().getTime() + 110*60*1000;
          @else
          var countDownDate = new Date().getTime() + 200*60*1000;
          @endif
          if (localStorage.getItem("timeCountDownQ1S1") !== null)
            var countDownDate = localStorage['timeCountDownQ1S1'];
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
              $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{ url('/timeOutQ1S1') }}",
                success:function(data)
                {
                  console.log("SUCCESS");
                  window.location = "{{ url('/Q1S3Start') }}";
                }
            });
              clearInterval(x);
            }
          }, 1000);
      </script>
      <form method="post" action="{{ url('/Q1VocabularyQ10SubmitData')}}" id="yourForm" name="yourForm">
      {{ csrf_field() }}

        <div class="container box"> 
          <div class="container box">
            <br>
            <p>{!! $data[0]->getText()!!}</p>
            <br />
          </div>

            @foreach($data as $post)
            @if(mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceA()))))))) < 15 
             && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceB()))))))) < 15
             && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceC()))))))) < 15
             && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceD()))))))) < 15)
            <div class = "test">
              <p><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>  </p>      
                <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
                <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
                <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
                <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
            </div>
            <div class = "test-information-q5s2q3">
                @if(!(app()->isProduction()))
                <i>id: {{$post->getDatabaseQuestionId()}}      |||||correct answer: {{$post->getCorrectChoice()}}||||| passage: {{$post->getPassage()}}</i>
                @endif
            </div>

            @elseif(mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceA()))))))) < 36
            && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceB()))))))) < 36
            && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceC()))))))) < 36 
            && mb_strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceD()))))))) < 36)
            <div class = "test">
                <p><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>  </p>      
                  <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
                  <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
                  <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
                  <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
            </div>
            <div class = "test-information-q5s2q3">
                @if(!(app()->isProduction()))
                <i>id: {{$post->getDatabaseQuestionId()}}      |||||correct answer: {{$post->getCorrectChoice()}} ||||| passage: {{$post->getPassage()}}</i>
                @endif
            </div>
            @else
                <div class = "test">
                <p><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>  </p>      
                  <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
                  <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
                  <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
                  <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q1S1Q10->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
            </div>
            <div class = "test-information-q5s2q3">
                @if(!(app()->isProduction()))
                <i>id: {{$post->getDatabaseQuestionId()}}      |||||correct answer: {{$post->getCorrectChoice()}} ||||| passage: {{$post->getPassage()}}</i>
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
    </div>  
 </body> 
</html>
