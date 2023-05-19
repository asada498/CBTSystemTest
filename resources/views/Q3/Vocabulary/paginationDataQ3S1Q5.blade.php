@inject('Q3S1Q5', 'App\Http\Controllers\Q3\Vocabulary\Q3S1Q5Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q3S1Q5</title>
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
    <div class="question2_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>５</div>
    <div class="question2_right">つぎの<ruby><rb>言</rb><rt>こと</rt><rb>葉</rb><rt>ば</rt></ruby>の<ruby><rb>使</rb><rt>つか</rt></ruby>い<ruby><rb>方</rb><rt>かた</rt></ruby>として<ruby><rb>一</rb><rt>いち</rt><rb>番</rb><rt>ばん</rt>いいものを、それぞれａ・ｂ・ｃ・ｄの中から１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
  </div>
  <div class = "title1">          <img src="{{ asset('image/3Q/example/q3s1q5example.png') }}" style="width:1700px" alt="Image"/></div>
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
                url:"{{ url('/saveChoiceRequestQ3S1Q5') }}",
//url:'/nat-test/saveChoiceRequestQ3S1Q5',
//url:'/saveChoiceRequestQ3S1Q5',
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
    </script>

  <form method="post" action="{{ url('/Q3VocabularyQ5SubmitData')}}" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
  @foreach($data as $post)
      <div class = "test">
      <p class = 'question'><span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
        <span class = "test-information">
            @if(!(app()->isProduction()))
            <i>Id:{{$post->getDatabaseQuestionId()}}      |||||    Vocabulary:{{$post->getPartOfSpeech()}}        |||||correct answer: {{$post->getCorrectChoice()}}</i>
            @endif
        </span></p>
        <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q3S1Q5->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!!$post->getChoiceA()!!}</label></span>
        <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q3S1Q5->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!!$post->getChoiceB()!!}</label></span>
        <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q3S1Q5->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!!$post->getChoiceC()!!}</label></span>
        <span class = "c3"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q3S1Q5->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!!$post->getChoiceD()!!}</label></span><br>
      </div>
      @endforeach
      @if (!$data-> hasMorePages())
<!--
      <div class="submit-button">
      <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary extra-first-page-padding" value="つぎの　もんだいへ" onclick="submitFunction();"/>
      </div>
-->
      <div class="submit-button-last-page">
      <label class = "end-of-part-label">これで　だいいちぶんやの　しけんは　おわりです</label>
      <input id = "submitButton" type="button" name="yourForm" class="btn btn-primary last-page-button" value="だいにぶんやへ" onclick="submitFunction();"/>
      </div>
      @endif

  </div>
  </form>
  <div class = "footer"> {{ $data->links() }}</div>

 </body>
</html>