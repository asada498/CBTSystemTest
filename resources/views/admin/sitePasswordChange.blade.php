<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>会場管理者パスワード一覧</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <style>
    th,td{
      height: 50px;
      vertical-align: middle;
      padding: 0 15px;
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
 </head>
 <body>
   <h3 align="center">会場管理者パスワード一覧</h3>
   <br>
   <form method="post" name="myForm" action="{{ url('/admin/sitePasswordRegist') }}">
   {{ csrf_field() }}
     <h3>
        <div style="height:551px; width:1326px; overflow-y:scroll;">
          <table style="margin-left: 430px;">
              <th class="fixed01">No.</th>
              <th class="fixed01">国</th>
              <th class="fixed01" style="width: 270px;">会場</th>
              <th class="fixed01" style="width: 200px;">パスワード</th>
@php
              $index = 0;
@endphp
              @foreach($data as $rec)
              <tr>
                  <td>{{$rec->getTestSite()}}<input type="hidden" name="testSite[@php echo $index; @endphp]" value="{{$rec->getTestSite()}}"/></td>
                  <td>{{$rec->getCountry()}}</td>
                  <td>{{$rec->getCity()}}</td>
                  <td><input type="text" name="password[@php echo $index; @endphp]" value="{{$rec->getPassword()}}" maxlength="50" required/></td>
              </tr>
@php
              $index++;
@endphp
              @endforeach
          </table>
        </div>
        <br><br>
        <input type="button" value="キャンセル" onclick="history.back();" style="width: 15%; margin-left: 510px;"/>
        <input type="submit" value="OK" style="width: 15%; margin-left: 50px;"/>
     </h3>
   </form>
  </div>
 </body>
</html>