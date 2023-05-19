<!DOCTYPE html>
<html>
 <head>
  <title>Picture Page</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
 </head>
 <script type="text/javascript">
function preLoadImage(){
    var img = new Image();
    var imgUrl = 'https://drive.google.com/uc?export=view&id={{$imageId}}';
    img.onload = function(){
        // this gets run once the image has finished downloading
        document.getElementById('pp').src = imgUrl;
        document.getElementById('box-confirmation').style.display = "block";

    }
    img.src = imgUrl; // this causes the image to get downloaded in the background
}
</script>

 <body onload="preLoadImage()">
  <br />
  <div id = "box-confirmation" class="container box-confirmation">
   <!-- <h3 align="center">Success</h3><br /> -->
    @if(Session::has('login') && Session::get('login') == true)
    <div class="alert alert-danger success-block">
     <!-- <p align="center"><strong>Please check your information below:</strong></p> -->
     <br />
     <!-- <p><strong>ID:{{$id}}</strong></p>
     <p><strong>Name: {{$name}}</strong></p>
     <p><strong>Level of test taking: {{$degree}}</strong></p>
     <p><strong>Your picture:</strong></p> -->
     <p align="center"><image id="pp" style="max-width: 550px;max-height: 650px;"></image></p>
     <div class = "level-confirmation">
     <strong><p>あなたは　{{$degree[0]}}きゅうを　じゅけんしますね。</p>
        <p>Do you want to take {{$degree[0]}}-level test, don't you ? </p></strong>
    </div>
     <!-- <p align="center"><strong>If your information above is correct, press YES. Otherwise, press No</strong></p> -->

    <form align="center" method="post" action="{{ url('/main/successlogin') }}">
        @csrf
        <button type="submit" name="status" value="yes">はい</button>
        <button type="submit" name="status" value="no">いいえ</button>
     </form>

    </div>
    @endif
   <br />
  </div>
 </body>
</html>
