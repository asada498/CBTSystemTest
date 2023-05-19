@inject('Q5S2Q1', 'App\Http\Controllers\Q5\Reading\Q5S2Q1Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q5S1Q2P2</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <div  class = "time-border">  <h4>あと<label class = "time-style" id="japaneseSentence">　</label>ふん</h4></div>
  @if($data->currentPage() == 1)
  <div class = "title1">          <img src="{{ asset('image/q5p2q1example.png') }}" style="width:1600px" alt="Image"/></div>
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
                url:'/nat-test/saveChoiceRequestQ5S2Q1',
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

  <form method="post" action="{{ url('/Q5ReadingQ1SubmitData')}}" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
      @foreach($data as $post)
      @if(strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceA()))))))) < 30 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceB()))))))) < 30 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceC()))))))) < 30 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceD()))))))) < 30)
      <!-- <div class = "test">
        <p class = 'question'><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
        <div class = "test-information">   <i>part of speech:{{$post->getPartOfSpeech()}}     |||||    correct answer: {{$post->getCorrectChoice()}}</i></div></p> -->
        <div class = "test">
      <p class = 'question'><span><strong><span class = "single-digit-question-border">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
        <span class = "test-information">
            @if(!(app()->isProduction()))
            <i>id: {{$post->getDatabaseQuestionId()}}      |||||    Grammar:{{$post->getPartOfSpeech()}}     |||    anchor:{{$post->getAnchor()}}     |||    correct answer: {{$post->getCorrectChoice()}}</i>
            @endif
        </span>
      </p>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q5S2Q1->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q5S2Q1->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q5S2Q1->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q5S2Q1->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
      </div>
      @else
      <div class = "test">
      <p class = 'question'><span><strong><span class = "single-digit-question-border">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
        <span class = "test-information">
            @if(!(app()->isProduction()))
            <i>id: {{$post->getDatabaseQuestionId()}}      |||||    Grammar:{{$post->getPartOfSpeech()}}      |||    anchor:{{$post->getAnchor()}}     |||    correct answer: {{$post->getCorrectChoice()}}</i>
            @endif
        </span>
      </p>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q5S2Q1->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!! $post->getChoiceA() !!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q5S2Q1->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!! $post->getChoiceB() !!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q5S2Q1->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!! $post->getChoiceC() !!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q5S2Q1->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!! $post->getChoiceD() !!}</label></span><br>
      </div>
      @endif
      @endforeach
      @if($data->currentPage() == 2)
      <div class="submit-button space-top">
      <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary" value="つぎの　もんだいへ" onclick="submitFunction();"/>
      </div>
      @endif
  </div>
  </form>

  <div class = "footer-q5s2q1"> {{ $data->links() }}</div>
 </body>
</html>