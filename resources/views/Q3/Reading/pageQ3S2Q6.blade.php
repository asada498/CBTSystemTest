@inject('Q3S2Q5', 'App\Http\Controllers\Q3\Reading\Q3S2Q5Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q3S2Q6</title>
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
        <div class="question2_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>６</div>
        <div class="question3_right">次の<ruby><rb>文</rb><rt>ぶん</rt><rb>章</rb><rt>しょう</rt></ruby>を読んで、後の<ruby><rb>問</rb><rt>と</rt></ruby>いに答えてください。答えはそれぞれａ・ｂ・ｃ・ｄの中から、<ruby><rb>一</rb><rt>いち</rt><rb>番</rb><rt>ばん</rt></ruby>いいものを１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
      </div>
      <script>
          function myFunction(id) {
              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  type:'POST',
                  url:"{{ url('/saveChoiceRequestQ3S2Q6') }}",
//url:'/nat-test/saveChoiceRequestQ3S2Q6',
//url:'/saveChoiceRequestQ3S2Q6',
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
          var countDownDate = new Date().getTime() + 70*60*1000;
          @else
          var countDownDate = new Date().getTime() + 100*60*1000;
          @endif
          if (localStorage.getItem("timeCountDownQ3S2") !== null)
            var countDownDate = localStorage['timeCountDownQ3S2'];
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
              
            // If the count down is over, write some text 
            if (distance < 1000) {
              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{ url('/timeOutQ3S2') }}",
                //url:'/nat-test/timeOutQ3S2',
                success:function(data)
                {
                  window.location = "{{ url('/Q3S3Start') }}";
                  //window.location = "/nat-test/Q3S3Start";
                }
              });
              clearInterval(x);
            }
          }, 1000);
      </script>
      <form method="post" action="{{ url('/Q3ReadingQ6SubmitData')}}" id="yourForm" name="yourForm">
      {{ csrf_field() }}

        <div class="container box"> 
          <div class="container box">
            <br>
            <p>{!! $data[0]->getText()!!}</p>
            <br />
          </div>

            @foreach($data as $post)
            @if(strlen($post->getChoiceA()) < 20 && strlen($post->getChoiceB()) < 20 && strlen($post->getChoiceB()) < 20 && strlen($post->getChoiceB()) < 20)
            <div class = "test">
              <p><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>  </p>      
                <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
                <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
                <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
                <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
            </div>
            <div class = "test-information-q5s2q3">
                @if(!(app()->isProduction()))
                <i>id: {{$post->getDatabaseQuestionId()}}      |||||correct answer: {{$post->getCorrectChoice()}}||||| passage: {{$post->getPassage()}}</i>
                @endif
            </div>
                
            @elseif (strlen($post->getChoiceA()) < 60 && strlen($post->getChoiceB()) < 60 && strlen($post->getChoiceB()) < 60 && strlen($post->getChoiceB()) < 60)
            <div class = "test">
                <p><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>  </p>      
                  <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
                  <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
                  <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
                  <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
            </div>
            <div class = "test-information-q5s2q3">
                @if(!(app()->isProduction()))
                <i>id: {{$post->getDatabaseQuestionId()}}      |||||correct answer: {{$post->getCorrectChoice()}} ||||| passage: {{$post->getPassage()}}</i>
                @endif
            </div>
            @else
                <div class = "test">
                <p><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>  </p>      
                  <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
                  <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
                  <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
                  <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q3S2Q5->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
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

