@inject('Q5S1Q2', 'App\Http\Controllers\Q5\Vocabulary\Q5S1Q2Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q5S1Q2</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

 </head>
 <body>
  <div  class = "time-border">  <h4>あと<label class = "time-style" id="japaneseSentence">　</label>ふん</h4></div>
  @if($data->currentPage() == 1)
  <div class = "title1 extra-first-page-padding">          <img src="{{ asset('image/q5p1q2example.png') }}" style="width:1700px" alt="Image"/></div>
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
                url:'/nat-test/saveChoiceRequestQ5S1Q2',
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
    </script>

  <form method="post" action="{{ url('/Q5VocabularyQ2SubmitData')}}" id="yourForm" name="yourForm">
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
                <i>id:{{$post->getDatabaseQuestionId()}}      |||||    Vocabulary:{{$post->getPartOfSpeech()}}      |||||    Kanji_Writing: {{$post->getGroup1()}}    |||||    correct answer: {{$post->getCorrectChoice()}}</i>
            @endif
        </span></p>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q5S1Q2->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {{$post->getChoiceA()}}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q5S1Q2->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {{$post->getChoiceB()}}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q5S1Q2->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {{$post->getChoiceC()}}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q5S1Q2->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {{$post->getChoiceD()}}</label></span><br>
      </div>
      @endforeach
      <div class="submit-button space-top">
      <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary" value="つぎの　もんだいへ" onclick="submitFunction();"/>
      </div>
  </div>
  
  </form>
  <div class = "footer"> {{ $data->links() }}</div>

 </body>
</html>