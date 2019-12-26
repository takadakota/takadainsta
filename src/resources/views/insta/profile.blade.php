<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>プロフィール画面</title>
        <link href="insta.css" rel="stylesheet" type="text/css">
        <header>
          <center><a href="/home">ホーム</a>
          <a href="/logout">ログアウト</a>
          <a href="/beforetweet">投稿</a></center>
        </header>
    </head>
  <body>
    <center>
    <table class="profiletable" width="350" height="100">
      <tr>
        <th width="100"><img src="{{ $avatar }}" class="inner_photo"></th>
        <th width="100" style="border-style: none;">{{ $username }}</th>
        <th width="150" style="border-style: none;">いいね合計数：{{ $count }}</th>
      </tr>
    </table>
    <ol>
    <br>
      <h3>ツイート一覧</h3>
    <br>
  </center>
      @isset ($usertweet)
      @foreach($usertweet as $tweet)
          <img src="{{ asset('storage/' . $tweet->imagepath) }}" class="usertweetimg">
      @endforeach
      @endisset
    </ol>
    </body>
</html>
