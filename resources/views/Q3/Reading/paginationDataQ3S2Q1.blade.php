@inject('Q3S2Q1', 'App\Http\Controllers\Q3\Reading\Q3S2Q1Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q3S2Q1</title>
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
    <div class="question2_left"><ruby><rb>問</rb><rt>もん</rt><rb>題</rb><rt>だい</rt></ruby>１</div>
    <div class="question2_right">つぎの<ruby><rb>文</rb><rt>ぶん</rt></ruby>の（　　）に何を入れますか。それぞれａ・ｂ・ｃ・ｄの中から<ruby><rb>一</rb><rt>いち</rt><rb>番</rb><rt>ばん</rt>いいものを１つ<ruby><rb>選</rb><rt>えら</rt></ruby>んでください。</div>
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
                url:"{{ url('/saveChoiceRequestQ3S2Q1') }}",
//url:'/nat-test/saveChoiceRequestQ3S2Q1',
//url:'/saveChoiceRequestQ3S2Q1',
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

  <form method="post" action="{{ url('/Q3ReadingQ1SubmitData')}}" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
  @foreach($data as $post)
      <div class = "test">
      @if($post->getQuestionId() > 9) 
      <p class = 'question'><span><strong><span class = "question-border">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
      @else
      <p class = 'question'><span><strong><span class = "single-digit-question-border">{{$post->getQuestionId()}}</span>　{!! $post->getQuestion() !!}</strong></span>
      @endif
      @if(strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceA()))))))) < 40 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceB()))))))) < 40 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceC()))))))) < 40 
      && strlen(str_replace("<ruby>", '', str_replace("</ruby>", '', str_replace("<rb>", '', str_replace("</rb>", '', str_replace("<rt>", '', str_replace("</rt>", '', $post->getChoiceD()))))))) < 40)
        <span class = "test-information">
            @if(!(app()->isProduction()))
            <i>Id:{{$post->getDatabaseQuestionId()}}      |||||    Grammar:{{$post->getGrammarClass()}}      ||||| anchor:{{$post->getAnchor()}}      |||||correct answer: {{$post->getCorrectChoice()}}</i>
            @endif
        </span></p>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q3S2Q1->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!!$post->getChoiceA()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q3S2Q1->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!!$post->getChoiceB()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q3S2Q1->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!!$post->getChoiceC()!!}</label></span>
        <span class = "c1"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q3S2Q1->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!!$post->getChoiceD()!!}</label></span><br>
      </div>
      @else
      <span class = "test-information">
          @if(!(app()->isProduction()))
          <i>Id:{{$post->getDatabaseQuestionId()}}      |||||    Grammar:{{$post->getGrammarClass()}}      ||||| anchor:{{$post->getAnchor()}}      |||||correct answer: {{$post->getCorrectChoice()}}</i>
          @endif
      </span></p>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q3S2Q1->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {!!$post->getChoiceA()!!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q3S2Q1->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {!!$post->getChoiceB()!!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q3S2Q1->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {!!$post->getChoiceC()!!}</label></span>
        <span class = "c2"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q3S2Q1->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {!!$post->getChoiceD()!!}</label></span><br>
      </div>
      @endif
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