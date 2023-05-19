<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>成績検索</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script type = "text/javascript">
    function loadFinished()
    {
        window.document.myForm.examineeId.value = '{{$condition->getExamineeId()}}';
        window.document.myForm.targetDate.value = '{{$condition->getTargetDate()}}';
        window.document.myForm.fromDate.value = '{{$condition->getFromDate()}}';
        window.document.myForm.toDate.value = '{{$condition->getToDate()}}';
        window.document.myForm.op.value = '{{$condition->getOp()}}';

        if('{{$condition->getAutoSearch()}}' == 1)
        {
@php
            $condition->setAutoSearch(0);
@endphp
            window.document.myForm.submit();
        }
    }

    function edit(examineeId){
        window.document.myForm2.editExamineeId.value = examineeId;
	      window.document.myForm2.submit();
    }

  </script>
</head>
<body onload="loadFinished()">
  <h3 align="center">成績検索</h3>

  <form method="post" name="myForm" action="{{ url('/grade/searchGradeList') }}">
    {{ csrf_field() }}
        <br>
<h3>
        <table style="margin-left: 100px;">
          <tbody>
            <tr>
              <td style="width: 150px;">受験者番号</td><td style="width: 500px;"><input type="text" name="examineeId" maxlength="14"/></td>
              <td style="width: 200px; text-align: center;">
                  <input type="radio" id="search" name="op" value="search" checked><label for="search">検索</label>　
                  <input type="radio" id="csv" name="op" value="csv"><label for="csv">CSV</label>
              </td>
            </tr>
            <tr>
              <td>試験日</td><td><input type="date" name="targetDate" /></td>
            </tr>
            <tr>
              <td>範囲指定</td><td><input type="date" name="fromDate" />～<input type="date" name="toDate" /></td>
            </tr>
          </tbody>
        </table>
</h3>
        <h3>
            <input type="reset" style="width: 10%; margin-left: 385px" value="クリア" />　　
            <input type="submit" style="width: 10%" value="検索" />
        </h3>
  </form>

  <form method="post" name="myForm2" action="{{ url('/grade/editGrade') }}">
    {{ csrf_field() }}
    <input type="hidden" name="editExamineeId" value="" />
   
    <label style="margin-left: 100px;">{{count($data)}}件</label>

    <table style="margin-left: 100px;">
        <thead>
            <tr>
                <th></th>
                <th style="text-align: center; width: 150px;">受験番号</th>
                <th style="text-align: center; width: 250px;">姓名</th>
                <th style="text-align: center;">生年月日</th>
                <th style="text-align: center;">成績票</th>
                <th style="text-align: center;">合格証</th>
                <th style="text-align: center;">合否判定</th>
                <th style="text-align: center;">アンカー<br>得点</th>
                <th style="text-align: center;">アンカー<br>得点/配点(%)</th>
                <th style="text-align: center;">合計</th>
                <th style="text-align: center;">１部門</th>
                <th style="text-align: center;">２部門</th>
                <th style="text-align: center;">３部門</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $rec)
            <tr>
                <td>
<!--
                    <input type="button" value="編集" onclick="edit('{{$rec->getExamineeId()}}');" style="width :120px;">
-->
                </td>
                <td style="text-align: center;">{{$rec->getExamineeId()}}</td>
                <td>&nbsp;                      {{$rec->getName()}}</td>
                <td style="text-align: center;">{{$rec->getBirthDay()}}</td>
                <td style="text-align: center;">{{$rec->getGradesCertificate()}}</td>
                <td style="text-align: center;">{{$rec->getPassCertificate()}}</td>
                <td style="text-align: center;">{{$rec->getPassFail()}}</td>
                <td style="text-align: center;">{{$rec->getAnchorScore()}}</td>
                <td style="text-align: center;">{{$rec->getAnchorPassRate()}}</td>
                <td style="text-align: center;">{{$rec->getScore()}}</td>
                <td style="text-align: center;">{{$rec->getSec1Score()}}</td>
                <td style="text-align: center;">{{$rec->getSec2Score()}}</td>
                <td style="text-align: center;">{{$rec->getSec3Score()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
  </form>
</body>
</html>