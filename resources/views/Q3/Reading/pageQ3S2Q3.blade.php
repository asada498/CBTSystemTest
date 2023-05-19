@inject('Q3S2Q3', 'App\Http\Controllers\Q3\Reading\Q3S2Q3Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q3S2Q3</title>
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
 <br />
 <div class="question">
    <div class="question2_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>３</div>
    <div class="question2_right">つぎの（ 19 ）から（ 23 ）に何を入れますか。それぞれａ・ｂ・ｃ・ｄの中から<ruby><rb>一</rb><rt>いち</rt><rb>番</rb><rt>ばん</rt>いいものを１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
  </div>
    <script>
        function myFunction(id) {
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{ url('/saveChoiceRequestQ3S2Q3') }}",
//url:'/nat-test/saveChoiceRequestQ3S2Q3',
//url:'/saveChoiceRequestQ3S2Q3',
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
var countDownDate = new Date().getTime() + 70*60*1000;
@else
var countDownDate = new Date().getTime() + 100*60*1000;
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
  <form method="post" action="{{ url('/Q3ReadingQ3SubmitData')}}" id="yourForm" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
      <p class = "text-question-4-s3">{!! $questionText !!}</p>
      @foreach($questionData as $post)
      @if(strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceA()))))))) < 40 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceB()))))))) < 40 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceC()))))))) < 40 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceD()))))))) < 40)
      <div class = "test">
      <span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span></strong></span>        
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q3S2Q3->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q3S2Q3->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q3S2Q3->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q3S2Q3->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
      </div>
      <div class = "test-information-q5s2q3">
          @if(!(app()->isProduction()))
          <i>Id:{{$post->getDatabaseQuestionId()}}      |||||    Grammar:{{$post->getGrammarClass()}}      |||||  correct answer: {{$post->getCorrectChoice()}}</i>
          @endif
      </div>
      @else
      <div class = "test">
      <span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span></strong></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q3S2Q3->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q3S2Q3->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
        <span class = "new-line-padding c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q3S2Q3->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
        <span class = "new-line-padding c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q3S2Q3->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
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