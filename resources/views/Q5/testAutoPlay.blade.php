<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<script>
//    function playSound() {
          //var sound = document.getElementById("au");
          //sound.play();
    //}
</script>
<title>タイトル</title>
</head>
<body>
<form name="myform">
	<audio controls autoplay id="au">
		<source src="{{ asset('05 トラック 05.wav') }}">
	</audio>
	<input type="button" value="st" name="st" id="st" onclick="au.play();"/>
	
	<input type="button" value="stst" name="stst" onclick="myform.st.click();"/>
	
	<!--
	<input type="button" value="stst" name="stst" onclick="alert('aaa');"/>
	-->
</form>
</body>
</html>


