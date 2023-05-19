@inject('Q5S1Q3', 'App\Http\Controllers\Q5\Vocabulary\Q5S1Q3Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q5S1Q3</title>
  <meta name="csrf-token" content="{{ csrf_token() }}"> 
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
 </head>
 <body>
  <div class = "title1">          <img src="{{ asset('image/q5p1q3example.png') }}" style="width:1700px" alt="Image"/></div>


  <div id="data-container"></div>
  <div id="pagination-container"></div>
    <script>        
        function myFunction(id) {
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',

                url:'/saveChoiceRequestQ5S1Q3',
                data:{name:id.name, answer:id.value},
                success:function(data)
                {
                  // alert(data.success);
                }
            });
        }
    </script>

  <form method="post" action="{{ url('/Q5VocabularyQ3SubmitData')}}" name="yourForm">
  {{ csrf_field() }}

  <div class="container box">
      @foreach($data as $post)
      <div class = "test-picture-question">
        <p>
            <span><strong><span style="border: 1px solid ">{{$post->getQuestionId()}}</span>　{{$post->getQuestion()}} </strong></span>
            <span class = "test-information">   <i>id: {{$post->getDatabaseQuestionId()}}      |||||    part of speech:{{$post->getPartOfSpeech()}}      |||||    anchor:{{$post->getAnchorStatus()}}      |||||correct answer: {{$post->getCorrectChoice()}}</i></span></p>

        </p>     
        <div>
            <div class = "column-answer-picture">
                <div class = "picture-answer-format"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}a" value="a" {{ $Q5S1Q3->checkValue($post->getQuestionId(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}a">&nbsp a. {{$post->getChoiceA()}}</label></div>
                <div class = "picture-answer-format"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}b" value="b" {{ $Q5S1Q3->checkValue($post->getQuestionId(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}b">&nbsp b. {{$post->getChoiceB()}}</label></div>
                <div class = "picture-answer-format"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}c" value="c" {{ $Q5S1Q3->checkValue($post->getQuestionId(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}c">&nbsp c. {{$post->getChoiceC()}}</label></div>
                <div class = "picture-answer-format"><input type="radio" name="{{$post->getQuestionId()}}" id = "{{$post->getQuestionId()}}d" value="d" {{ $Q5S1Q3->checkValue($post->getQuestionId(),"d") }} onclick=" myFunction(this)" /><label for="{{$post->getQuestionId()}}d">&nbsp d. {{$post->getChoiceD()}}</label></div>
            </div>
            <div class = "column-picture">
                <div><img src="{{url('/image/'.$post->getImageId().'.bmp')}}" style="height:240px" alt="Image"/> </div>
            </div>
        </div>
        <br />
      </div>
      @endforeach
  </div>
  </form>

 </body>
</html>