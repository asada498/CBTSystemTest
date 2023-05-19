<!DOCTYPE html>
<html>
<head>
    <title>Capture tester's picture</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <style type="text/css">
        #results { 
            /* padding:20px;
            border:1px solid;
            background:#ccc; */
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center"><ruby><rb>写真</rb><rt>しゃしん</rt></ruby>を<ruby><rb>撮</rb><rt>と</rt></ruby>りましょう。Please take your picture in here.</h1>
   
    <form id="myFormId" method="POST" action="{{ url('/submitTesteePicture')}}">
    {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                <div id="my_camera"></div>
                <br/>
                <button type="button" onclick="take_snapshot()" id="take"><ruby><rb>写真</rb><rt>しゃしん</rt></ruby>を<ruby><rb>撮</rb><rt>と</rt></ruby>る　Take your snapshot</button>
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-md-4">
                <div id="results">Your captured image will appear here...</div>
            </div>

        </div>

        <h2 class="text-center" id="syashin" style="color: white">
            <p><ruby><rb>写真</rb><rt>しゃしん</rt></ruby>を<ruby><rb>撮</rb><rt>と</rt></ruby>り<ruby><rb>直</rb><rt>なお</rt></ruby>しますか？</p>
            <p>Do you want to retake the photo?</p>
        </h1>
        <br>
        <h2 class="text-center">
            <div id = "d1">
            </div>
        </h2>
        <br>
        <h2><div id="d2" class="text-center">
            <!--
            <button onclick="submit_picture()" id = "submit-picture-button" class="btn btn-success" disabled><ruby><rb>提出</rb><rt>ていしゅつ</rt></ruby>　Submit</button>
    -->
        </div></h2>
        <br/>
    </form>
</div>
  
<!-- Configure a few settings and attach camera -->
<script language="JavaScript">

    Webcam.set({
        width: 533,
        height: 400,
        image_format: 'jpeg',
        jpeg_quality: 90,
        crop_width: 300,
        crop_height: 400
    });

    Webcam.attach( '#my_camera' );
  
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        } );
        //document.getElementById("submit-picture-button").disabled = false;
        document.getElementById('take').disabled = true;
        
        document.getElementById('syashin').style = "color: black";

        const d1 = document.getElementById("d1");
        const b1 = document.createElement("input");
        b1.type = "button"
        b1.style = "width:200px";
        b1.addEventListener("click", yes_event);
        b1.value = "はい　YES";
        b1.id = "yes_button";
        d1.appendChild(b1);

        const t1 = document.createTextNode("　");
        d1.appendChild(t1);

        const b2 = document.createElement("input");
        b2.type = "button"
        b2.style = "width:200px";
        b2.addEventListener("click", no_event);
        b2.value = "いいえ　NO";
        b2.id = "no_button";
        d1.appendChild(b2);
    }
    
    let yes_event = function() {
        window.location='{{ url('/testeePicture')}}';
    };

    let no_event = function() {
        document.getElementById("yes_button").disabled = true;
        document.getElementById("no_button").disabled = true;

        const d2 = document.getElementById("d2");
        const b2 = document.createElement("input");
        b2.type = "button";
        b2.addEventListener("click", submit_picture);
        b2.id="submit-picture-button";
        b2.class="btn btn-success";
        b2.value = "ていしゅつ　Submit";
        d2.appendChild(b2);
    };

    let submit_picture = function() {
        $("#myFormId").submit();
        document.getElementById("submit-picture-button").disabled = true;
    };
</script>
 
</body>
</html>