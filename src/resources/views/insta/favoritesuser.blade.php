<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>いいねしたユーザ画面</title>
        <link href="insta.css" rel="stylesheet" type="text/css">
        <header>
          <center><a href="/home">ホーム</a>
          <a href="/logout">ログアウト</a>
          <a href="/beforetweet">投稿</a></center>
        </header>
    </head>
  <body>
  <div class="d-flex justify-content-center">
    <center>
      <h3>いいねしている人リスト</h3>
      <br>
      @foreach($favusers as $favuser)
      @foreach($usersdata as $userdata)
      @if($favuser->favorite==1)
      @if($favuser->user_id==$userdata->user_id)
      <table class="profile">
        <tr>
          <th style="border-style: none;">
            <form action="{{ url('/profile') }}" method="POST">
              <input type="hidden" name="user_id" value="{{ $userdata->user_id }}">
              {{ csrf_field() }}
              <button class="link-style-btn"><img src="{{ $userdata->avatar }}"></button>
            </form>
          </th>
          <th style="border-style: none;">
            <form action="{{ url('/profile') }}" method="POST">
              <input type="hidden" name="user_id" value="{{ $userdata->user_id }}">
              {{ csrf_field() }}
              <button class="link-style-btn">{{ $userdata->username }}</button>
            </form>
          </th>
        </tr>
      </table>
      @endif
      @endif
      @endforeach
      @endforeach
    </center>
  </div>
  </body>
</html>
