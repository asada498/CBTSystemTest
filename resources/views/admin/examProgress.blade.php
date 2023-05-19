<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Exam Progress</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    th,td{
      height: 30px;
      vertical-align: middle;
      padding: 0 10px;
      border: 1px solid #ccc;
    }
    .fixed01{
      position: sticky;
      top: 0;
      color: #fff;
      background: #333;
      &:before{
        content: "";
        position: absolute;
        top: -1px;
        left: -1px;
        width: 100%;
        height: 100%;
        border: 1px solid #ccc;
      }
    }
  </style>
  <script>
      function pic(id, name){
        window.document.myForm2.id.value = id;
        window.document.myForm2.nm.value = name;
        window.document.myForm2.action="{{ url('/admin/pictureExamProgress') }}";
	      window.document.myForm2.method="post";
	      window.document.myForm2.target="_blank";
        window.document.myForm2.submit();
      }
      function check()
      {
          return confirm("Are you sure?");
      }
      function func(target, targetId, reasonObj){
          window.document.myForm2.btn.value = target.value;
          window.document.myForm2.id.value = targetId;
          window.document.myForm2.rsn.value = reasonObj.value;
          window.document.myForm2.country.value = window.document.myForm.country.value;
          window.document.myForm2.city.value = window.document.myForm.city.value
          window.document.myForm2.testDay.value = window.document.myForm.testDay.value;
          window.document.myForm2.uid.value = window.document.myForm.uid.value;
          window.document.myForm2.target="_self";
          window.document.myForm2.action="{{ url('/admin/registExamProgress') }}";
      }
  </script>
</head>
<body>
  <?php

use Illuminate\Support\Facades\DB;

    $password = Session::get('password');
    $countries = DB::table('test_information')
    ->selectRaw('country')
    ->groupBy('country')
    ->orderBy('country')
    ->get();
  ?>
  <h3 align="center">Exam Progress</h3>
  
  <br>
  <form method="post" name="myForm" action="{{ url('/admin/searchExamProgress') }}">
      {{ csrf_field() }}
      <h3>
        @if($password=="aikkamata2255" or $password=="tokyo")
          <select onchange="getCities(this)" style="margin-left: 100px; background-color: beige; width: 300px;" name="country">
          <option  value="0">-- --</option>
          @foreach($countries as $country)
            <option  value="{{$country->country}}">{{$country->country}}</option>
          @endforeach
          </select>
          <select  style="background-color: beige; width: 300px;" name="city" id="city">
          </select>
        @else
          <input style="margin-left: 100px; background-color: beige" name="country" value="{{$condition->getCountry()}}" readonly/>
          <input style="background-color: beige;" name="city" value="{{$condition->getCity()}}" readonly/>
        @endif
        
        <!--
        <input type="date" name="testDay" value="{{$condition->getTestDay()}}" style="margin-left: 50px;"/>
        -->
        <label>　受験番号<br>　Examniee's number　</label><input type="text" name="uid" value="{{$condition->getID()}}" />
        <input type="submit" value="search" style="margin-left: 50px;"/>
        <!--<button type="button" onclick="location.href='{{ url('/admin/supervisorMenu') }}'" style="margin-left: 100px;">Menu</button>-->
      </h3>
  </form>
  <form method="post" name="myForm2" action="{{ url('/admin/registExamProgress') }}" onsubmit="return check();">
    {{ csrf_field() }}
    <br>
    <div style="height:551px; width:1600px; overflow-y:scroll;">
    <table style="margin-left: 100px;">
    <tr>
        <th style="width: 4%;" class="fixed01">Time</th>
        <th style="width: 8%;" class="fixed01">ExamineeId</th>
        <th style="width: 5%;" class="fixed01">Password</th>
        <th style="width: 20%;" class="fixed01">Name</th>
        <th style="width: 5%;" class="fixed01">Photo</th>
        <th style="width: 2%;" class="fixed01">Lv.</th>
        <th style="width: 5%;" class="fixed01">Login</th>
        <th style="width: 8%;" class="fixed01">Progress</th>
        <th style="width: 14%;" class="fixed01">RE-Start</th>
        <th style="width: 5%;" class="fixed01">Cheat</th>
        <th class="fixed01">Reason</th>
    </tr>
@php
    $len = count($list);
    $i = 0;
@endphp
    @foreach($list as $rec)
    <tr>
        <td>{{$rec->getTime()}}</td>
        <td>{{$rec->getExamineeId()}}</td>
        <td>{{$rec->getPassword()}}</td>
        <td>{{$rec->getName()}}</td>
        <td><button type="button" name="mode" value="PHOTO" onClick="pic('{{$rec->getExamineeId()}}', '{{$rec->getName()}}');" >PHOTO</button></td>
        <td>{{$rec->getLevel()}}</td>
        <td>{{$rec->getLogin()}}</td>
        <td>{{$rec->getProgress()}}</td>
@if ($len == 1)
        <td><button type="submit" name="mode" value="RESTART" onClick="func(this, '{{$rec->getExamineeId()}}', window.document.myForm2.reason);" {{$rec->getReStartDisabled()}}>{{$rec->getReStart()}}</button></td>
        <td><button type="submit" name="mode" value="CHEAT"   onClick="func(this, '{{$rec->getExamineeId()}}', window.document.myForm2.reason);" {{$rec->getCheatDisabled()}}>CHEAT</button></td>
@else
        <td><button type="submit" name="mode" value="RESTART" onClick="func(this, '{{$rec->getExamineeId()}}', window.document.myForm2.reason[{{$i}}]);" {{$rec->getReStartDisabled()}}>{{$rec->getReStart()}}</button></td>
        <td><button type="submit" name="mode" value="CHEAT"   onClick="func(this, '{{$rec->getExamineeId()}}', window.document.myForm2.reason[{{$i}}]);" {{$rec->getCheatDisabled()}}>CHEAT</button></td>
@endif
        <td><input type="text" name="reason" value="{{$rec->getReason()}}" size="35" maxlength="255"/></td>
        <!--
        <td>　</td>
        -->
    </tr>
@php
    $i++;
@endphp
    @endforeach
    </table>
    </div>
    <input type="hidden" name="btn" value=""/>
    <input type="hidden" name="id" value=""/>
    <input type="hidden" name="rsn" value=""/>
    <input type="hidden" name="country" value=""/>
    <input type="hidden" name="city" value=""/>
    <input type="hidden" name="testDay" value=""/>
    <input type="hidden" name="uid" value=""/>
    <input type="hidden" name="nm" value="" />
  </form>
  
</body>
</html>
<script type="text/javascript">
  function getCities(selectOS) {
    let country = selectOS.value;
    //alert(country);

    $.ajax({
        url: 'get_city/{country}',
        type: 'GET',
        data: { country: country },
        success: function(response)
        {
            if(response.message="success")
            { 
              //console.log(response.html);
              city.innerHTML = response.html;
            }
        }
    });   
  }
</script>  