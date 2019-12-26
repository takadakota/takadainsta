<!doctype html>
<html lang=ja>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="insta.css" rel="stylesheet" type="text/css">
        <title>ログイン画面</title>
    </head>
    <form action="{{ url('/login/github')}}" method="GET">
    <center>
    <richbtn>
      <input type="submit" value="ログインする">
    </richbtn>
    </center>
    </form>
</html>
