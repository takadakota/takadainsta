<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Model\Tweet;
use App\Model\Favorite;
class InstaController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){//ログイン状態でアクセスしたらHomeにリダイレクト
      if (Auth::check()) {
        return redirect('/home');//viewだとTweet読み込まれずエラー
      }else{
        return view('insta/login');
      }
    }

    public function home(Request $request){
      //ログインしてる人がいいねしてるツイートのデータを抜き出したい
      //ログインしている人のuser_id（単数）のデータと一致するtweet_id（複数）を取得する
      $favorites = Favorite::get();//いいね！に関しては全部取得して、条件絞りをHTMLで行っている
      $Tweet = Tweet::orderBy('tweet_id', 'desc')->simplePaginate(3);//ツイート全部を読み込んで降順に並び替えてる
      return view('insta/home', ["Tweet"=>$Tweet,"favorites"=>$favorites]);
    }

    public function beforetweet(Request $request){
      //もしログインしてなかったら/homeにredirect
        if (Auth::check()) {
          return view('insta/beforetweet');//viewだとTweet読み込まれずエラー
        }else{
          return redirect('/home');
        }
      }

    public function favoritesuser(Request $request){
      //Whereの第２引数に複数の値を入れたい！→View側でforeach入れ子で実装、実装後：こっちでif文とか使えば別にできたような気がする。
      $tweet_id = $request->input('tweet_id');//tweet_idの中身：tweet_id。。。のはずがなぜか空白になる1/6、解決：javascriptの部分のform id名が一緒だった
      $favusers = Favorite::where('tweet_id',$tweet_id)->get();//tweet_idが一致していていいねしている行を取得、いいねしてるかなど細かい判定はViewで行う
      $usersdata = User::get();//一番新しいtweet_idに引っ張られる→わざわざ実装したjavascriptが悪さしてたのでaタグでの値渡しをボツにして解決
      return view('insta/favoritesuser',["tweet_id"=>$tweet_id,"favusers"=>$favusers,"usersdata"=>$usersdata]);
    }

    public function profile(Request $request){
      //Tweetテーブルで対応する主キーをここで読み込んで、View側でforeach
      //いいね数カウント実装12/19
      $user_id = $request->input('user_id');
      $usertweet = Tweet::where('user_id',$user_id)->orderBy('created_at', 'desc')->get();//12/19クリックしたユーザの名前に対応するつぶやきを取得
      $username = User::where('user_id',$user_id)->value('username');//個別に取り出してるのはforeachを使わないから
      $avatar = User::where('user_id',$user_id)->value('avatar');
      $count = Favorite::where('user_id',$user_id)->where('favorite',1)->count();//ここリレーションお試しポイント
      //$count = Tweet::where('user_id',$user_id)->favorites()->where('favorite',1)->count();//リレーションしたかったけどうまくいかない
      return view('insta/profile', ["usertweet" => $usertweet,"count"=>$count,"username"=>$username,"avatar"=>$avatar]);
    }

    //viewで指定するのはファイルの位置っぽい→Viewはinsta/〜にする

    /**
    * ファイルアップロード処理
    */
    public function upload(Request $request)//画像とテキストが送られてくる。ここではそれらをテーブルに入れたい。
    {
      $request->validate([
        'file' => [
            // 必須
            'required',//kbyte、60MBが最大という制約
            // アップロードされたファイルであること
            'file',
            // 画像ファイルであること
            'image',
            // MIMEタイプを指定
            'mimes:jpeg,png,gif',//gif追加
        ]//これらは制約みたいなもん、validateメソッドの仕様、書き方
    ]);
      $request->validate([
                'caption' => 'required|max:200',//Viewにもerror出力文書いたけど下のelse以下で処理してる？、とりあえず動くのでこのまま
            ]);

      if ($request->file('image')->isValid([])) {//ファイルの中身があるなら
          //$path = $request->file('file')->store('public');//ツイートされた画像を保存
          //$image = basename($path);//basename()→（）のいる場所を除いたパスデータ
          $image = base64_encode(file_get_contents($request->image->getRealPath()));
          $caption = $request -> input('caption');//inputの使い方とか時間があれば細かく見たい、requestとセットで使うのでは
          $user = Auth::user();//Userテーブルからget()で持ってくるとデータがコレクション型で　"カラム名:〜"　みたいになる
          $user_id = $user->user_id;
          $username = $user->username;
          $avatar = $user->avatar;
          $now = date("Y/m/d H:i:s");//ツイートしたときの日付時刻データ、降順するのに使う
          $tweet_id = Tweet::create(['user_id'=>$user_id,'username'=>$username ,'avatar'=>$avatar, 'image'=>$image , 'caption'=>$caption ,'created_at'=>$now, 'updated_at'=>$now]);
          Favorite::create(['tweet_id'=>$tweet_id->tweet_id ,'user_id'=>$user_id,'favorite'=>null,'created_at'=>$now, 'updated_at'=>$now]);
          return redirect('/home');//redirectにした、そのため「, ["Tweet" => $Tweet]」の引数渡しはルーティング後のhome関数で呼び出されてる
      }else {
          return redirect()
            ->back()
            ->withInput()
            ->withErrors();
    }
}
    public function delete(Request $request)//つぶやきテーブルのレコードを消したい
    {//消す標的はtweet_idで決めたい
      $delete_id = $request -> input('delete_id');//hiddenで持ってきた、delete_idの中身ばtweet_id
      Tweet::find($delete_id) -> delete();
      Favorite::where('tweet_id',$delete_id) -> delete();//Favoriteからも対象tweet_idのものを削除する必要がある
      //$favorites = Tweet::find($delete_id)->favorites;//この←と↓２行はリレーションの動作確認みたいな、ほんとに理解してるか使えるかテスト、ダメでした12/25/15:23
      //$favorites -> delete();
      return redirect('/home');
  }
    public function good(Request $request)//いいねの挙動
    { //いいねボタン押す、nullなら１入れる、１ならnullにする
      $user_id = Auth::id();//ログインしてる人のid
      $tweet_id = $request->input('tweet_id');//hiddenで持ってくる、該当ツイートのid
      $hantei=Favorite::where('tweet_id',$tweet_id)->where('user_id',$user_id)->value('favorite');//同じtweet_id、user_idで検索して対象のレコードのいいね状態取得
      if($hantei==null){//nullなら１を入れる、null以外がいいねしてる状態
        Favorite::where('tweet_id',$tweet_id)->where('user_id',$user_id)->update(['favorite'=>1]);
      }else{//null以外ならnullにする
        Favorite::where('tweet_id',$tweet_id)->where('user_id',$user_id)->update(['favorite'=>null]);
      }
      return redirect('/home');
    }
}
