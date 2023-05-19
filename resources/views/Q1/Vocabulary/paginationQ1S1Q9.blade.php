<!DOCTYPE html>
<html>
 <head>
  <title>Q1VocabularyQ9</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script type="text/javascript" src="{{ URL::asset('js/rightclickdisable.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/keyboarddisable.js') }}"></script>
  <script>
    function preventBack() {
        window.history.forward();
    }

    setTimeout("preventBack()", 0);
    window.onunload = function() {
        null
    };
</script>
 </head>
 <body>
  <div class="box">
   <div id="table_data">
    @include('Q1\Vocabulary\paginationDataQ1S1Q9')
   </div>
   <script>
// Set the date we're counting down to
@if(app()->isProduction())
var countDownDate = new Date().getTime() + 110*60*1000 ;
@else
var countDownDate = new Date().getTime() + 200*60*1000 ;
@endif

if (localStorage.getItem("timeCountDownQ1S1") !== null)
  var countDownDate = localStorage['timeCountDownQ1S1'];
  
// Update the count down every 1 second
var x = setInterval(function() {

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
                url:"{{ url('/timeOutQ1S1') }}",
                success:function(data)
                {
                  console.log("SUCCESS");
                  window.location = "{{ url('/Q1S3Start') }}";
                }
            });
    clearInterval(x);
  }
}, 1000);
  </script>
  </div>
 </body>
</html>

<script>
$(document).ready(function(){

 $(document).on('click', '.pagination a', function(event){
  event.preventDefault(); 
  var page = $(this).attr('href').split('page=')[1];
  fetch_data(page);
 });

 function fetch_data(page)
 {
  $.ajax({

   //url:"/nat-test/Q1VocabularyQ9/fetchData?page="+page,
   //url:"/Q1VocabularyQ9/fetchData?page="+page,
    url:"{{ url('/Q1VocabularyQ9/fetchData?page=') }}"+page, 

   success:function(data)
   {
    $('#table_data').html(data);
   }
  });
 }
 
});
</script>