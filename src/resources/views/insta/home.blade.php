<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ホーム画面</title>
        <link href="insta.css" rel="stylesheet" type="text/css">
        <header>
          <center><a href="/home">ホーム</a><!-- ダブルクオーテーションの中身はURL、URLに反応してコントローラの関数に飛ぶ -->
          @if( Auth::check() )
          {{ csrf_field() }}
          <a href="/logout">ログアウト</a>
          @else
          <a href="/login">ログイン</a>
          @endif
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
        function submitFavoritesuser(){
          //formオブジェクトを取得する
          var fm = document.getElementById("submit");
          //Submit形式指定する（post/get）
          fm.method = "post";  // 例）POSTに指定する、追記:action、methodともにhtml内に記述だとエラーだった
          //action先を指定する
          fm.action = "/favoritesuser";  // 例）"/php/sample.php"に指定する
          //Submit実行
          fm.submit();
        }
        </script>
    </head>
    <body>
    @if ($errors->any())
      <h2>エラーメッセージ</h2>
      <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif
      <div class="d-flex justify-content-center">
        <ol>
        @isset ($Tweet)
        @foreach($Tweet as $tweet)
        <center>
        <table class="bigframetable" width="320" height="400">
          <tr><th>
        <table class="smallframetable" width="300" height="380" align="center">
          <tr>
            <th align="center" style="border-style: none;">
              <form id="submit">
                <input type="hidden" name="user_id" value="{{ $tweet->user_id }}">
                {{ csrf_field() }}
                <a href="javascript:submitProfile();">{{ $tweet->username }}</a>
              </form>
            </th>
            <th align="right" style="border-style: none;">
              @if( Auth::check() )
                <form action="{{ url('/delete') }}" method="POST">
                  <input type="hidden" name="delete_id" value="{{ $tweet->tweet_id }}">
                  {{ csrf_field() }}
                  <button class="btn btn-success">投稿を削除</button>
                </form>
              @endif
            </th>
          </tr>
          <tr>
            <th height="250" colspan="2"><img src="{{ asset('storage/' . $tweet->imagepath) }}" class="inner_photo"></th>
          </tr>
          <tr>
            <td align="left" valign="top" height="100" colspan="2" style="border-style: none;">{{$tweet->caption}}</td>
          </tr>
          <tr>
            <th align="left" style="border-style: none;">
              <form id="submit">
                <input type="hidden" name="tweet_id" value="{{ $tweet->tweet_id }}">
                {{ csrf_field() }}
                <a href="javascript:submitFavoritesuser()">いいねした人</a>
              </form>
            </th>
            @if( Auth::check() )

            @foreach($favorites as $favorite)
            @if($tweet->tweet_id==$favorite->tweet_id)
            @if($favorite->user_id==Auth::id())
            @if($favorite->favorite==1)
            <!-- いいねしてない時の見た目 -->
              <th align="right" style="border-style: none;">
                <form name="good" method="POST" action="{{ url('/good') }}">
                  <input type="hidden" name="tweet_id" value="{{ $tweet->tweet_id }}"><!-- ツイートのid送る、Controllerでログインしてる人のid読み取る、組み合わせて一つのツイートを検索する -->
                    {{ csrf_field() }}
                  <button class="button2">いいね！</button>
                </form>
              </th>
            @elseif($favorite->favorite==null)
            <!-- いいねしてる時の見た目 -->
              <th align="right" style="border-style: none;">
                <form name="good" method="POST" action="{{ url('/good') }}">
                  <input type="hidden" name="tweet_id" value="{{ $tweet->tweet_id }}"><!-- ツイートのid送る、Controllerでログインしてる人のid読み取る、組み合わせて一つのツイートを検索する -->
                  {{ csrf_field() }}
                  <button class="button1">いいね！</button>
                </form>
              </th>
            @endif
            @endif
            @endif
            @endforeach

            @endif
          </tr>
         </table>
         </th></tr>
        </table>
        <br>
        </center>
        @endforeach
        @endisset
      </ol>
      <center>{{ $Tweet -> links() }}</center><!-- 次へ、前へボタンの元。この一文の位置に出現 -->
      </div>
    </body>
</html>
