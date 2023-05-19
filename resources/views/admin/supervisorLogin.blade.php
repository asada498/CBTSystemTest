<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>examiner</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <script>
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
  </script>
  <style>
    table.example,
    table.example th,
    table.example td {
        border: 0px #000000 solid;
    }
  </style>
</head>
<body>
  <br/>
  <div class="container">
    <div class = "title-login">
      <h3 align="center">Supervisor</h3>
      <br/>
    </div>

   @if ($message = Session::get('error'))
   <div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
   </div>
   @endif

   @if (count($errors) > 0)
    <div class="alert alert-danger">
     <ul>
     @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
     @endforeach
     </ul>
    </div>
   @endif
   
   <form method="post" name="myForm" action="{{ url('/admin/checkLoginSupervisor') }}">
    {{ csrf_field() }}
    <h3>
        <table class="example" style="margin-left: 300px;">
        <tr>
            <td style="width: 200px;">Country</td>
            <td>
                <select name="country" onChange="onChangeCountry()" required>
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
            </td>
        </tr>
        <tr>
            <td>City</td>
            <td>
                <select name = "city" required></select>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td>Password</td>
            <td>
                <input type="password" name="password" required maxlength="50"/>
            </td>
        </tr>           
        </table>
   </h3>
    <div class="form-group login-button">
     <input type="submit" name="login" class="btn btn-primary" value="Login" />
    </div>
   </form>
  </div>
 </body>
</html>