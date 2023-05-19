@inject('Q4S2Q4', 'App\Http\Controllers\Q4\Reading\Q4S2Q4Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q4S2Q4</title>
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
  <div class = "title1">          <img src="{{ asset('image/4Q/example/q4p2q4example.png') }}" alt="Image"/></div>
    <script>
        function myFunction(id) {
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'/nat-test/saveChoiceRequestQ4S2Q4',
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
  var countDownDate = new Date().getTime() + 55*60000 ;
@else
  var countDownDate = new Date().getTime() + 100*60000 ;
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
  var minutes = Math.floor((distance % (1000 * 60 * 60 * 60)) / (1000 * 60))+1;
    
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

function scrollDown(clicked_id)
        {
          $('html').animate({scrollTop: $(window).scrollTop() + $(window).height()}, '500');    
          // document.getElementById("triangle-arrow-up").style.display = "block";
          // document.getElementById("triangle-arrow-down").style.display = "none";
        }

        function scrollUp(clicked_id)
        {
          $('html').animate({ scrollTop: 0 }, '500');
          // document.getElementById("triangle-arrow-down").style.display = "block";
          // document.getElementById("triangle-arrow-up").style.display = "none";
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
    </script>

<form method="post" action="{{ url('/Q4ReadingQ4SubmitData')}}" id="yourForm" name="yourForm">
  {{ csrf_field() }}
  <div class="container box">

  @foreach($data as $post)

      <p class = "title-question-number">({{$post->getQuestionId()-21}})</p>
      <p class = "text-title-question-4-s2">{!! $post->getText() !!}</p>
      @if(strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceA()))))))) < 30 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceB()))))))) < 30 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceC()))))))) < 30 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceD()))))))) < 30)      <div class = "test">
      <p><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>  </p>      
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q4S2Q4->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!!$post->getChoiceA()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q4S2Q4->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!!$post->getChoiceB()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q4S2Q4->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!!$post->getChoiceC()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q4S2Q4->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!!$post->getChoiceD()!!}</label></span><br>
      </div>

      <div class = "test-information-q5s2q3">
          @if(!(app()->isProduction()))
          <i>id: {{$post->getDatabaseQuestionId()}}      |||||Reading: {{$post->getReadingClass()}}      |||||correct answer: {{$post->getCorrectChoice()}}</i>
          @endif
      </div>
      @else
      <div class = "test">
      <p><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span></p>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q4S2Q4->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!!$post->getChoiceA()!!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q4S2Q4->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!!$post->getChoiceB()!!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q4S2Q4->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!!$post->getChoiceC()!!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q4S2Q4->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)"  /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!!$post->getChoiceD()!!}</label></span><br>
      </div>
      <div class = "test-information-q5s2q3">
          @if(!(app()->isProduction()))
          <i>id: {{$post->getDatabaseQuestionId()}}      |||||Reading: {{$post->getReadingClass()}}      |||||correct answer: {{$post->getCorrectChoice()}}</i>
          @endif
      </div>

      @endif
  @endforeach
  <a id = "triangle-arrow-down" class = "triangle-arrow-down" onclick = " scrollDown(this)"></a>
      <a id = "triangle-arrow-up" class = "triangle-arrow-up" onclick = " scrollUp(this)"></a>
      <div class="submit-button">
      <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary" value="つぎの　もんだいへ" onclick="submitFunction();"/>
      </div>

  </div>
  </div>

  </form>

 </body>
</html>