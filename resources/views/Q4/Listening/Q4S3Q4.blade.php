@inject('Q4S3Q4', 'App\Http\Controllers\Q4\Listening\Q4S3Q4Controller')

<!DOCTYPE html>
<html>
 <head>
  <title>Q4S4Q3</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}" >
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
  <script>   
          
  function init() {
                var sample = document.getElementById("sample");
                sample.addEventListener("ended", function() { var ban1 = document.getElementById("ban1"); ban1.play(); });
                var ban1 = document.getElementById("ban1");
                ban1.addEventListener("ended", function() { var audio1 = document.getElementById("audio1"); audio1.play(); });
                var audio1 = document.getElementById("audio1");
                audio1.addEventListener("ended", function() { var ban2 = document.getElementById("ban2"); ban2.play(); });
                var ban2 = document.getElementById("ban2");
                ban2.addEventListener("ended", function() { var audio2 = document.getElementById("audio2"); audio2.play(); });
                var audio2 = document.getElementById("audio2");
                audio2.addEventListener("ended", function() { var ban3 = document.getElementById("ban3"); ban3.play(); });
                var ban3 = document.getElementById("ban3");
                ban3.addEventListener("ended", function() { var audio3 = document.getElementById("audio3"); audio3.play(); });
                var audio3 = document.getElementById("audio3");
                audio3.addEventListener("ended", function() { var ban4 = document.getElementById("ban4"); ban4.play(); });
                var ban4 = document.getElementById("ban4");
                ban4.addEventListener("ended", function() { var audio4 = document.getElementById("audio4"); audio4.play(); });
                var audio4 = document.getElementById("audio4");
                audio4.addEventListener("ended", function() { var ban5 = document.getElementById("ban5"); ban5.play(); });
                var ban5 = document.getElementById("ban5");
                ban5.addEventListener("ended", function() { var audio5 = document.getElementById("audio5"); audio5.play(); });
                var audio5 = document.getElementById("audio5");
                audio5.addEventListener("ended", function() { var ban6 = document.getElementById("ban6"); ban6.play(); });
                var ban6 = document.getElementById("ban6");
                ban6.addEventListener("ended", function() { var audio6 = document.getElementById("audio6"); audio6.play(); });
                var audio6 = document.getElementById("audio6");
                audio6.addEventListener("ended", function() { var ban7 = document.getElementById("ban7"); ban7.play(); });
                var ban7 = document.getElementById("ban7");
                ban7.addEventListener("ended", function() { var audio7 = document.getElementById("audio7"); audio7.play(); });
                var audio7 = document.getElementById("audio7");
                audio7.addEventListener("ended", function() { var ban8 = document.getElementById("ban8"); ban8.play(); });
                var ban8 = document.getElementById("ban8");
                ban8.addEventListener("ended", function() { var audio8 = document.getElementById("audio8"); audio8.play(); });
                var audio8 = document.getElementById("audio8");
                audio8.addEventListener("ended", function() { window.document.yourForm.submit(); });
  }

  function myFunction(id) {
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type:'POST',
      url:'/nat-test/saveChoiceRequestQ4S3Q4',
      data:{name:id.name, answer:id.value},
      success:function(data)    
      {
        // alert(data.success);
      }
  });
  }
</script> 

</head>
  
{{-- onload="init();" --}}
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
<body style="font-size: 19px;" onload="init();">

        <audio id ="sample" autoplay>
          <source  src="{{ asset('/audio/5Q/20-2/25 Track25.mp3') }}" type="audio/mpeg" />
        </audio>

        <audio id="ban1"  preload="none">
          <source src="{{ url('/audio/4Q/'.$data[0]->getBanFile()) }}">
        </audio>
        <audio id="audio1" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[0]->getListening()) }}">
        </audio>
        <audio id="ban2" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[1]->getBanFile()) }}">
        </audio>
        <audio id="audio2" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[1]->getListening()) }}">
        </audio>
        <audio id="ban3" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[2]->getBanFile()) }}">
        </audio>
        <audio id="audio3" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[2]->getListening()) }}">
        </audio>
        <audio id="ban4" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[3]->getBanFile()) }}">
        </audio>
        <audio id="audio4" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[3]->getListening()) }}">
        </audio>
        <audio id="ban5" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[4]->getBanFile()) }}">
        </audio>
        <audio id="audio5" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[4]->getListening()) }}">
        </audio>
        <audio id="ban6" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[5]->getBanFile()) }}">
        </audio>
        <audio id="audio6" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[5]->getListening()) }}">
        </audio>
        <audio id="ban7" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[6]->getBanFile()) }}">
        </audio>
        <audio id="audio7" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[6]->getListening()) }}">
        </audio>
        <audio id="ban8" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[7]->getBanFile()) }}">
        </audio>
        <audio id="audio8" preload ="none">
          <source src="{{ url('/audio/4Q/'.$data[7]->getListening()) }}">
        </audio>
        <div class = "title1">
              <img src="{{ asset('image/4Q/example/q4s3q4example.png') }}" alt="Image"/>
        </div>
           
        <div class="box">
          <div id="table_data">
            <form method="post" action="{{ url('/Q4ListeningQ4SubmitData')}}" name="yourForm">
              {{ csrf_field() }}
@if(!(app()->isProduction()))
　　　<button type="button" onclick="window.document.yourForm.submit();" style="vertical-align: right;">skip</button>
@endif
              <div class="container box">
                <div class="test">  
                  @foreach($data as $post)
                  <p class = 'sec3-ban'><span class="sec3-ban">&emsp;{{$post->getNo()}}ばん</span>
@if(!(app()->isProduction()))
                  &emsp;&emsp;&emsp; &emsp;&emsp;&emsp;&emsp; &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; &emsp;&emsp;&emsp;&emsp; &emsp;&emsp;&emsp;
                  Question Id---   {{$post->getQid()}}
                  Answer--{{$post->getAnswer()}}
                  Listening--{{$post->getListeningClass()}}
                  Listening_Group--{{$post->getListeningGroup()}}
                  Anchor--{{$post->getAnchor()}}
@endif
                  <br>
                  <p>
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$post->getNo()}}" id="{{$post->getNo()}}a" value="a" {{ $Q4S3Q4->checkValue($post->getNo(),"a") }} onclick=" myFunction(this)" /><label for="{{$post->getNo()}}a">&nbsp a.</label></span>
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$post->getNo()}}" id="{{$post->getNo()}}b" value="b" {{ $Q4S3Q4->checkValue($post->getNo(),"b") }} onclick=" myFunction(this)" /><label for="{{$post->getNo()}}b">&nbsp b.</label></span>
                  <span class="sec3-q4-c1-tight"><input type="radio" name="{{$post->getNo()}}" id="{{$post->getNo()}}c" value="c" {{ $Q4S3Q4->checkValue($post->getNo(),"c") }} onclick=" myFunction(this)" /><label for="{{$post->getNo()}}c">&nbsp c.</label></span>
                  </p>
                  @endforeach
                </div>
              </div>
            </form>
          </div>
        </div>
        <label class = "end-of-part-label Q5S3Q4-label">これで　だいさんぶんやの　しけんは　おわりです</label>

</body>
</html>