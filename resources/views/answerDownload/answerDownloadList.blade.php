<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>回答検索</title>
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
        window.document.myForm.level.value = '{{$condition->getLevel()}}';

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
  <h3 align="center">回答検索</h3>

  <form method="post" name="myForm" action="{{ url('/answerDownload/searchAnswerDownloadList') }}">
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
            <tr>
              <td>級</td><td><input type="text" name="level" /></td>
            </tr>
          </tbody>
        </table>
</h3>
        <h3>
            <input type="reset" style="width: 10%; margin-left: 385px" value="クリア" />　　
            <input type="submit" style="width: 10%" value="検索" />
        </h3>
  </form>

  <form method="post" name="myForm2" action="{{ url('/answerDownload/editAnswerDownload') }}">
    {{ csrf_field() }}
    <input type="hidden" name="editExamineeId" value="" />
   
    <label style="margin-left: 100px;">{{count($data)}}件</label>

    <table style="margin-left: 100px;">
        <thead>
            <tr>
                <th></th>
                <th style="text-align: center; width: 150px;">受験番号</th>
                <th style="text-align: center;">級</th>
                <th style="text-align: center;">問題ＩＤ</th>
                <th style="text-align: center;">分野</th>
                <th style="text-align: center;">大問</th>
                <th style="text-align: center;">小問</th>
                <th style="text-align: center;">解答</th>
                <th style="text-align: center;">正否</th>
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
                <td style="text-align: center;">{{$rec->getLevel()}}</td>
                <td style="text-align: center;">{{$rec->getQuestionId()}}</td>
                <td style="text-align: center;">{{$rec->getSection()}}</td>
                <td style="text-align: center;">{{$rec->getQuestionNumber()}}</td>
                <td style="text-align: center;">{{$rec->getNumber()}}</td>
                <td style="text-align: center;">{{$rec->getAnswer()}}</td>
                <td style="text-align: center;">{{$rec->getPassFail()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
  </form>
</body>
</html>