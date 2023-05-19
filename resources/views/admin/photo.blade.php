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
    <script>
    </script>
</head>
<body>
    <h3 align="center">{{$name}}</h3>
    <p align="center"><image src="https://drive.google.com/uc?export=view&id={{$imageId}}" style="max-width: 550px;max-height: 650px;"></image></p>
    <br>
    <h3 align="center"><button type="button" onclick="window.close();">close</button></h3>
</body>
</html>