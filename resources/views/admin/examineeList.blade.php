<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>入金、受験者情報検索</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script type = "text/javascript">
    function loadFinished()
    {
        window.document.myForm.examineeId.value = '{{$condition->getExamineeId()}}';
        window.document.myForm.name.value = '{{$condition->getName()}}';
        window.document.myForm.level.value = '{{$condition->getLevel()}}';
        window.document.myForm.testDay.value = '{{$condition->getTestDay()}}';
        window.document.myForm.op.value = '{{$condition->getOp()}}';

        if('{{$condition->getPaymentNotYet()}}' == '1'){
            window.document.myForm.paymentNotYet.checked = true;
        }
        if('{{$condition->getPaymentDone()}}' == '1'){
            window.document.myForm.paymentDone.checked = true;
        }
        if('{{$condition->getPhotoNotYet()}}' == '1'){
            window.document.myForm.photoNotYet.checked = true;
        }
        if('{{$condition->getPhotoDone()}}' == '1'){
            window.document.myForm.photoDone.checked = true;
        }
        country = "{{$condition->getCountry()}}";
        city = "{{$condition->getCity()}}";
        if(country != ""){
            for(i = 0; i < window.document.myForm.country.options.length; i++){
                if(window.document.myForm.country.options[i].value == country){
                    window.document.myForm.country.options[i].selected = true;
                    onChangeCountry();
                    if(city != ""){
                        for(j = 0; j < window.document.myForm.city.options.length; j++){
                            if(window.document.myForm.city.options[j].value == city){
                                window.document.myForm.city.options[j].selected = true;
                                break;
                            }
                        }
                    }
                    break;
                }
            }
        }
        if('{{$condition->getAutoSearch()}}' == 1)
        {
@php
            $condition->setAutoSearch(0);
@endphp
            window.document.myForm.submit();
        }
    }
    function BANGLADESH(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("DHAKA");            
    }
    function BHUTAN(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("THIMPHU");            
    }
    function CAMBODIA(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("PHNOM PENH");
    }
    function CHINA(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("BEIJING");
        city.options[2] = new Option("CHANGCHUN");
        city.options[3] = new Option("CHANGSHA");
        city.options[4] = new Option("CHENGDU");
        city.options[5] = new Option("CHONGQING");
        city.options[6] = new Option("DALIAN");
        city.options[7] = new Option("DONGGUAN");
        city.options[8] = new Option("FUQING");
        city.options[9] = new Option("FUXIN");
        city.options[10] = new Option("FUZHOU");
        city.options[11] = new Option("GUANGZHOU");
        city.options[12] = new Option("HA'ERBIN");
        city.options[13] = new Option("HAIYANG");
        city.options[14] = new Option("HANGZHOU");
        city.options[15] = new Option("HEFEI");
        city.options[16] = new Option("HUAIHUA");
        city.options[17] = new Option("HUHEHAOTE");
        city.options[18] = new Option("JIAXING");
        city.options[19] = new Option("JINAN");
        city.options[20] = new Option("KUNMING");
        city.options[21] = new Option("LINYI");
        city.options[22] = new Option("LVLIANG");
        city.options[23] = new Option("NANCHANG");
        city.options[24] = new Option("NANJING");
        city.options[25] = new Option("NANNING");
        city.options[26] = new Option("NINGBO");
        city.options[27] = new Option("QINGDAO");
        city.options[28] = new Option("RIZHAO");
        city.options[29] = new Option("RUSHAN");
        city.options[30] = new Option("SHANGHAI");
        city.options[31] = new Option("SHAOXING");
        city.options[32] = new Option("SHENYANG");
        city.options[33] = new Option("SHENZHEN");
        city.options[34] = new Option("SHIJIAZHUANG");
        city.options[35] = new Option("TAIZHOU");
        city.options[36] = new Option("TIANJIN");
        city.options[37] = new Option("WEIFANG");
        city.options[38] = new Option("WEIHAI");
        city.options[39] = new Option("WUHAN");
        city.options[40] = new Option("XI'AN");
        city.options[41] = new Option("XIAMEN");
        city.options[42] = new Option("XINING");
        city.options[43] = new Option("ZHENGZHOU");
    }
    function INDIA(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("CHENNAI");
        city.options[2] = new Option("NEW DELHI");
        city.options[3] = new Option("PUNE");
    }
    function INDONESIA(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("BANDUNG");
        city.options[2] = new Option("DENPASAR");
        city.options[3] = new Option("JAKARTA");
        city.options[4] = new Option("MAKASSAR");
        city.options[5] = new Option("MEDAN");
        city.options[6] = new Option("PADANG");
        city.options[7] = new Option("SURABAYA");
    }
    function JAPAN(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("FUKUOKA");
        city.options[2] = new Option("OSAKA");
        city.options[3] = new Option("TOKYO");
        city.options[4] = new Option("TOKYO-2");
    }
    function KYRGYZ(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("BISHKEK");
    }
    function MONGOLIA(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("ULAANBAATAR");
    }
    function MYANMAR(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("MANDALAY");
        city.options[2] = new Option("YANGON");
    }
    function NEPAL(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("CHITWAN");
        city.options[2] = new Option("ITAHARI");
        city.options[3] = new Option("KATHMANDU");
        city.options[4] = new Option("POKHARA");            
    }
    function PHILIPPINES(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("MANILA");
    }
    function SRILANKA(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("COLOMBO Site 1");
        city.options[2] = new Option("COLOMBO Site 2");
    }
    function THAILAND(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("BANGKOK");
    }
    function UZBEKISTAN(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("ANDIJAN");
        city.options[2] = new Option("SAMARKAND");
        city.options[3] = new Option("TASHKENT");
    }
    function VIETNAM(){
        var city = document.forms.myForm.city;
        city.options[0] = new Option("");
        city.options[1] = new Option("DANANG");
        city.options[2] = new Option("HAI DUONG");
        city.options[3] = new Option("HANOI (VNU) 2");
        city.options[4] = new Option("HANOI (VNU) 3");
        city.options[5] = new Option("HANOI Site 1");
        city.options[6] = new Option("HANOI Site 2");
        city.options[7] = new Option("HO CHI MINH Site 1");
        city.options[8] = new Option("HO CHI MINH Site 2");
        city.options[9] = new Option("THAI NGUYEN");
        city.options[10] = new Option("VINH");
    }
    function onChangeCountry()
    {
        var select1 = document.forms.myForm.country;
        document.forms.myForm.city.options.length = 0;
         
        switch(select1.options[select1.selectedIndex].value){
        case "BANGLADESH":
            BANGLADESH();
            break;
        case "BHUTAN":
            BHUTAN();
            break;
        case "CAMBODIA":
            CAMBODIA();
            break;
        case "CHINA":
            CHINA();
            break;
        case "INDIA":
            INDIA();
            break;
        case "INDONESIA":
            INDONESIA();
            break;
        case "JAPAN":
            JAPAN();
            break;
        case "KYRGYZ":
            KYRGYZ();
            break;
        case "MONGOLIA":
            MONGOLIA();
            break;
        case "MYANMAR":
            MYANMAR();
            break;
        case "NEPAL":
            NEPAL();
            break;
        case "PHILIPPINES":
            PHILIPPINES();
            break;
        case "SRI LANKA":
            SRILANKA();
            break;
        case "THAILAND":
            THAILAND();
            break;
        case "UZBEKISTAN":
            UZBEKISTAN();
            break;
        case "VIETNAM":
            VIETNAM();
            break;
        }
    }
    function edit(examineeId){
        window.document.myForm2.editExamineeId.value = examineeId;
	    window.document.myForm2.submit();
    }

  </script>
</head>
<body onload="loadFinished()">
<?php
    $password = Session::get('password'); 
?>
  <h3 align="center">入金、受験者情報検索</h3>
  <h3 align="center">Searching for payment status and Examinees' information</h3>

  <form method="post" name="myForm" action="{{ url('/admin/searchExamineeList') }}">
    {{ csrf_field() }}
        <br>
<h3>
        <table style="margin-left: 300px;">
          <tbody>
            <tr>
              <td style="width: 20%;">受験番号<br>Examinee's Number</td><td><input type="text" name="examineeId" maxlength="14"/></td>
              <td style="width: 20%;">名前<br>Name</td><td><input type="text" name="name" maxlength="255"/></td>
              <td style="width: 200px; text-align: center;">
                  <input type="radio" id="search" name="op" value="search" checked><label for="search">検索</label>　
                  <input type="radio" id="csv" name="op" value="csv"><label for="csv">CSV</label>
              </td>
            </tr>
            <tr>
              <td>国<br>Country</td>
              <td>
              @if($password=="aikkamata2255" or $password=="tokyo")
                <select name="country" onChange="onChangeCountry()">
                <option value=""></option>
                <option value="BANGLADESH">BANGLADESH</option>
                <option value="BHUTAN">BHUTAN</option>
                <option value="CAMBODIA">CAMBODIA</option>
                <option value="CHINA">CHINA</option>
                <option value="INDIA">INDIA</option>
                <option value="INDONESIA">INDONESIA</option>
                <option value="JAPAN">JAPAN</option>
                <option value="KYRGYZ">KYRGYZ</option>
                <option value="MONGOLIA">MONGOLIA</option>
                <option value="MYANMAR">MYANMAR</option>
                <option value="NEPAL">NEPAL</option>
                <option value="PHILIPPINES">PHILIPPINES</option>
                <option value="SRI LANKA">SRI LANKA</option>
                <option value="THAILAND">THAILAND</option>
                <option value="UZBEKISTAN">UZBEKISTAN</option>
                <option value="VIETNAM">VIETNAM</option>
                </select>
              @endif
              </td>
              <td>入金<br>Payment status</td>
              <td>
                <input type="checkbox" name="paymentNotYet" id="paymentNotYet" value="1"><label for="paymentNotYet">未 Not yet　</label>
                <input type="checkbox" name="paymentDone" id="paymentDone" value="1"><label for="paymentDone">済 Paid</label>
              </td>
            </tr>
            <tr>
              <td>会場<br>Test site</td>
              <td>
              @if($password=="aikkamata2255" or $password=="tokyo")
                <select name = "city"/>
              @endif  
              </td>
              <td>写真<br>Photograph</td>
              <td>
                <input type="checkbox" name="photoNotYet" id="photoNotYet" value="1"><label for="photoNotYet">未 Not yet　</label>
                <input type="checkbox" name="photoDone" id="photoDone" value="1"><label for="photoDone">済 Sent</label>
              </td>
            </tr>
            <tr>
              <td>試験日<br>Test day</td><td><input type="date" name="testDay" /></td>
              <td>級<br>Level</td>
              <td>
                <!--<input type="text" name="level" maxlength="1"/>-->
                <select name="level" style="width: 60%;">
                    <option value="">--</option>
                    <option value="1">Q1</option>
                    <option value="2">Q2</option>
                    <option value="3">Q3</option>
                    <option value="4">Q4</option>
                    <option value="5">Q5</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
</h3>
        <h3>
<!--
            <input type="button" style="width: 13%; margin-left: 300px" value="メニュー" onclick="window.location='{{ url('/admin/adminMenu') }}'" />
-->
            <button type="reset" style="width: 10%; margin-left: 1100px" >クリア　Clear</button>　　
            <button type="submit" style="width: 10%" >検索　Search</button>
        </h3>
  </form>

  <form method="post" name="myForm2" action="{{ url('/admin/editExaminee') }}">
    {{ csrf_field() }}
    <input type="hidden" name="editExamineeId" value="" />
   
    <label style="margin-left: 300px;">{{count($data)}}件　Number of Examinees</label>

    <table style="margin-left: 300px;">
        <thead>
            <tr>
                <th></th>
                <th style="text-align: center; width: 150px;">受験番号<br>Examninee's Number</th>
                <th style="text-align: center; width: 250px;">名前<br>Name</th>
                <th style="text-align: center;">入金<br>Payment status</th>
                <th style="text-align: center;">写真<br>Photograph</th>
                <th style="text-align: center;">国<br>Country</th>
                <th style="text-align: center;">会場<br>Test site</th>
                <th style="text-align: center;">試験日<br>Test day</th>
                <th style="text-align: center;">級<br>Level</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $rec)
            <tr>
                <td><input type="button" value="編集　Edit" onclick="edit('{{$rec->getExamineeId()}}');" style="width :120px;"></td>
                <td style="text-align: center;">{{$rec->getExamineeId()}}</td>
                <td>&nbsp;{{$rec->getName()}}</td>
                <td style="text-align: center;">{{$rec->getPaymentDone()}}</td>
                <td style="text-align: center;">{{$rec->getPhotoDone()}}</td>
                <td>&nbsp;{{$rec->getCountry()}}</td>
                <td>&nbsp;{{$rec->getCity()}}</td>
                <td style="text-align: center;">{{$rec->getTestDay()}}</td>
                <td style="text-align: center;">{{$rec->getLevel()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
  </form>
</body>
</html>