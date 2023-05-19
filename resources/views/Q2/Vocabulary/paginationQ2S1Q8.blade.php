@inject('Q2S1Q8', 'App\Http\Controllers\Q2\Vocabulary\Q2S1Q8Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q2S1Q8</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
 </head>
 <body>
   <!--mana shu uslda biz adres maydoni va back buttonni yashira olamiz
  <input id="Button1" type="button" value="button" onclick="window.open('Q3VocabularyQ1','PoP_Up','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=1024,height=768');" />
  -->
  <br />
 <div class="box">
  <div  class = "time-border">  <h4>あと<label class = "time-style" id="japaneseSentence">　</label> ふん</h4></div>
  <br />
  <div class="question">
    <div class="question2_left">問題８</div>
    <div class="question2_right">次のａ・ｂ・ｃ・ｄを並べ替えて文を作ります。そのとき、<u>　★　</u>に入るものはａ・ｂ・ｃ・ｄのどれですか。一番いいものを１つ選んでください。</div>
  </div>
  <div class = "title1"><img src="{{ asset('image/2Q/example/q2s1q8example.png') }}" style="width:1700px" alt="Image"/></div>

  <p class="indent"></p>

    <script>
        function myFunction(id) {
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{ url('saveChoiceRequestQ2S1Q8') }}",
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
      
    // If the count down is over, write some text 
    if (distance < 1000) {    
      $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{ url('/timeOutQ2S1') }}",
                success:function(data)
                {
                  window.location = "{{ url('/Q2S3Start') }}";
                }
            });
      clearInterval(x);
    }
  }, 1000);
</script>    

  <form method="post" action="{{ url('/Q2VocabularyQ8SubmitData')}}" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
  @foreach($data as $post)
      <div class = "test">
      <p class = 'question'><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
        <span class = "test-information">
            @if(!(app()->isProduction()))
            <i>Id:{{$post->getDatabaseQuestionId()}}      |||||    Grammar:{{$post->getGrammarClass()}}  |||||     newQuestion:{{$post->checkNewQuestion()}}   |||||  |||||correct answer: {{$post->getCorrectChoice()}}</i>
            @endif
        </span></p>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q2S1Q8->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!!$post->getChoiceA()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q2S1Q8->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!!$post->getChoiceB()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q2S1Q8->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!!$post->getChoiceC()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q2S1Q8->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!!$post->getChoiceD()!!}</label></span><br>
      </div>
      @endforeach
      
      <div class="submit-button">
        <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary extra-first-page-padding" value="つぎの　もんだいへ" onclick="submitFunction();"/>
      </div>

  </div>
  </form>

 </body>
</html>