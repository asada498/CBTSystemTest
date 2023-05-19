@inject('controller', 'App\Http\Controllers\admin\ExamineeEditController')

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>受験者情報編集</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script type = "text/javascript">
    function paymentNotYetChecker(paymentDone){
        if(paymentDone == "0"){
            return "checked"
        }else{
            return "";
        }
    }
    function paymentDoneChecker(paymentDone){
        if(paymentDone == "1"){
            return "checked"
        }else{
            return "";
        }
    }
    function pic(id){
        window.document.myForm2.id.value = id;
        window.document.myForm2.action="{{ url('/admin/pictureExaminee') }}";
        window.document.myForm2.method="post";
	    window.document.myForm2.target="_blank";
        window.document.myForm2.submit();
    }
    function submitFunction(){
          document.getElementById("submitButton").disabled = true;
          document.getElementById("cancelButton").disabled = true;
          document.forms['myForm'].submit();
    }
  </script>
  <style>
    table.example,
    table.example th,
    table.example td {
        border: 0px #999999 solid;
    }
  </style>
</head>
<body>
  <h3 align="center">受験者情報編集</h3>
  <h3 align="center">Edit Examninees information</h3>
  <br>
  <form method="post" enctype="multipart/form-data" name="myForm" action="{{ url('/admin/registExaminee') }}">
    {{ csrf_field() }}
    <h3>
    <table class="example" style="margin-left: 200px;" >
        <tbody>
        <tr>
            <td style="width: 250px;">受験番号<br>Examinee's Number</td><td><input type="text" name="examineeId" value='{{$data->getExamineeId()}}' readonly />
            <td></td>
            <td style="width: 200px;">名前<br>Name</td><td><input type="text" name="name" style="width: 600px;" value='{{$data->getName()}}' maxlength="255" required/></td>
        </tr>
        <tr><td style="height: 10px;">&nbsp;</td></tr>
        <tr>
            <td>暗証<br>Code</td>
            <td><input type="text" name="pin" value='{{$data->getPin()}}' readonly /></td>
            <td></td>
            <td>入金<br>Payment status</td>
            <td>&nbsp;<input type="radio" name="payment" id="payment0" value="0" {{$data->getPaymentNotYet()}}/><label for="payment0">未 Not yet　</lavel>
                &nbsp;<input type="radio" name="payment" id="payment1" value="1" {{$data->getPaymentDone()}}/><label for="payment1">済 Paid</lavel>
            </td>
        </tr>
        <tr><td style="height: 10px;">&nbsp;</td></tr>
        <tr>
            <td>国<br>Country</td>
            <td><input type="text" name="country" value='{{$data->getCountry()}}' readonly /></td>
            <td></td>
            <td>誕生日<br>Birthday</td>
            <td><input type="date" name="birthDay" value='{{$data->getBirthDay()}}' required></td>
        </tr>
        <tr><td style="height: 10px;">&nbsp;</td></tr>
        <tr>
            <td>会場<br>Test site</td>
            <td><input type="text" name="city" value='{{$data->getCity()}}' readonly /></td>
            <td></td>
            <td>国<br>Country</td>
            <td><input type="text" name="countryAd" style="width: 600px;" value='{{$data->getCountryAd()}}' maxlength="30" required /></td>
        </tr>
        <tr><td style="height: 10px;">&nbsp;</td></tr>
        <tr>
            <td>試験日<br>Test day</td>
            <td><input type="text" name="testDay" value='{{$data->getTestDay()}}' readonly /></td>
            <td></td>
            <td>住所<br>Address</td>
            <td><input type="text" name="address" style="width: 600px;" value='{{$data->getAddress()}}' maxlength="255" required /></td>
        </tr>
        <tr><td style="height: 10px;">&nbsp;</td></tr>
        <tr>
            <td>級<br>Level</td>
            <td><input type="text" name="level" value='{{$data->getLevel()}}' readonly /></td>
            <td></td>
            <td>郵便番号<br>Zip code</td>
            <td><input type="text" name="zipcode" style="width: 600px;" value='{{$data->getZipcode()}}' maxlength="20" required /></td>
        </tr>
        <tr><td style="height: 10px;">&nbsp;</td></tr>
        <tr>
            <td>写真<br>Photograph</td>
            <td><label>{{$data->getPhoto()}}</label>&nbsp;&nbsp;<button type="button" onClick="pic('{{$data->getExamineeId()}}');" {{$data->getPhotoDisabled()}}>表示 Picture</button>
            </td>
            <td></td>
            <td>email</td>
            <td><input type="text" name="email" style="width: 600px;" value='{{$data->getEmail()}}' maxlength="100" required /></td>
        </tr>
        <tr><td style="height: 10px;">&nbsp;</td></tr>
        <tr>
            <td></td>
            <!-- <td><input id="fileButton" name="fileName"type="file"/></td> -->
            <td>
            <?php
             echo Form::open(array('url' => '/admin/registExaminee','files'=>'true'));
             echo Form::file('image');
             echo Form::close();
            ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <br><br>
    <input type="button" value="キャンセル　Cancel" id="cancelButton" onclick="history.back();" style="width :300px; margin-left: 550px;">
    <input type="button" value="登録　Register" id="submitButton" onclick="submitFunction();" style="width :300px; margin-left: 80px;">
    </h3>
  </form>
  <form method="post" name="myForm2">
    {{ csrf_field() }}
    <input type="hidden" name="id" value=""/>
  </form>
</body>
</html>