<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>投稿画面</title>
        <link href="insta.css" rel="stylesheet" type="text/css">
        <header>
          <center><a href="/home">ホーム</a>
          <a href="/logout">ログアウト</a>
          <a href="/beforetweet">投稿</a></center>
        </header>
    </head>
    <body>
    <center><h1>tweet画面</h1>
    <!-- フォームエリア -->
    <form action="{{ url('home') }}" method="POST" enctype="multipart/form-data">
        <label for="photo">投稿画像選択</label>
        <br>
        <input type="file" class="form-control" name="file">
        <br>
        つぶやき内容
        <br>
        <textarea name="caption" rows="4" cols="40"></textarea>
        <br>
        {{ csrf_field() }}
        <button class="btn btn-success"> つぶやく </button>
    </form>
  </center>
    </body>
</html>
