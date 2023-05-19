@inject('Q1S1Q3', 'App\Http\Controllers\Q1\Vocabulary\Q1S1Q3Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q1S1Q3</title>
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
  @if($data->currentPage() == 1)
  <div class="question">
    <div class="question2_left">問題３</div>
    <div class="question2_right">次の文の<u>　　</u>の言葉に一番近い意味のものを、それぞれａ・ｂ・ｃ・ｄの中から１つ選んでください。</div>
  </div>
  @else  
  <style>p.indent{ padding-top: 1% }</style>

  <p class="indent"></p>
  @endif
    <script>
        function myFunction(id) {
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{ url('/saveChoiceRequestQ1S1Q3') }}",
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
    </script>

  <form method="post" action="{{ url('/Q1VocabularyQ3SubmitData')}}" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
  @foreach($data as $post)
      <div class = "test">
      <p class = 'question'><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
        <span class = "test-information">
            @if(!(app()->isProduction()))
            <i>Id:{{$post->getDatabaseQuestionId()}}  |||||    Vocabulary:{{$post->getclassVocabulary()}}  |||||   newQuestion:{{$post->checkNewQuestion()}}  |||||     correct answer: {{$post->getCorrectChoice()}}</i>
            @endif
        </span></p>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q1S1Q3->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!!$post->getChoiceA()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q1S1Q3->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!!$post->getChoiceB()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q1S1Q3->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!!$post->getChoiceC()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q1S1Q3->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!!$post->getChoiceD()!!}</label></span><br>
      </div>
      @endforeach
      @if (!$data-> hasMorePages())

      <div class="submit-button">
      <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary extra-first-page-padding" value="つぎの　もんだいへ" onclick="submitFunction();"/>
      </div>
      @endif

  </div>
  </form>
  <div class = "footer"> {{ $data->links() }}</div>

 </body>
</html>