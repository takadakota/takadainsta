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
        <script>
        function submitProfile(){
          //formオブジェクトを取得する
          var fm = document.getElementById("submit");
          //Submit形式指定する（post/get）
          fm.method = "post";  // 例）POSTに指定する、追記:action、methodともにhtml内に記述だとエラーだった
          //action先を指定する
          fm.action = "/profile";  // 例）"/php/sample.php"に指定する
          //Submit実行
          fm.submit();
        }
        </script>
    </head>
  <body>
  <div class="d-flex justify-content-center">
    <center>
      @isset ($usersdata)
      @foreach($usersdata as $userdata)
      <table class="profile">
        <tr>
          <th style="border-style: none;">
            <form id="submit">
              <input type="hidden" name="user_id" value="{{ $userdata->user_id }}">
              {{ csrf_field() }}
              <a href="javascript:submitProfile();"><img src="{{ $userdata->avatar }}"></a>
            </form>
          </th>
          <th style="border-style: none;">
            <form id="submit">
              <input type="hidden" name="user_id" value="{{ $userdata->user_id }}">
              {{ csrf_field() }}
              <a href="javascript:submitProfile();">{{ $userdata->username }}</a>
            </form>
          </th>
        </tr>
      </table>
      @endforeach
      @endisset
    </center>
  </div>
  </body>
</html>
