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
      //リレーションでやりたいこと書き出し
      //ログインしてる人がいいねしてるツイートのデータを抜き出したい
      //ログインしている人のuser_id（単数）のデータと一致するtweet_id（複数）を取得する
      //$favorites = User::find(Auth::id())->favorites;//リレーション、ログインしてる人のいいね状態取得
      //$favorites = Favorite::where('user_id',Auth::id())->get;//上と同義
      $favorites = Favorite::get();//いいね！に関しては全部取得して、条件絞りをHTMLで行っている
      $Tweet = Tweet::orderBy('tweet_id', 'desc')->simplePaginate(3);//ツイート全部を読み込んで降順に並び替えてる、仕様は１ページ１０ツイートだけどテスト用で３ツイート
      return view('insta/home', ["Tweet" => $Tweet,"favorites"=>$favorites]); //, "favstate"=>$favstate]);//maruとbatsuがnullか１かでいいねしてるかどうか判定する（ゴリ押し感）
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
      //favoritesテーブル作ってそこにいる名前の人をViewで表示させたい
      //クリックしたツイートをいいねしている人のuser_id（複数）をtweet_idから取得
      //そのuser_id（複数）に紐づいたアイコン、ユーザ名を取得
      $tweet_id = $request->input('tweet_id');//tweet_idの中身：tweet_id
      $favusers = Favorite::where('tweet_id',$tweet_id)->where('favorite',1)->get()->toArray();//tweet_idが一致している行を取得、リレーションお試しポイント
      //$favusers = Favorite::where('tweet_id',$tweet_id)->where('favorite',1)->pluck('user_id');//ツイートに対して誰がいいねしてるか配列型で取得
      $usersdata = User::whereIn('user_id',$favusers)->get();//配列で受け取る時はWhereIn、$favusers->get('user_id')、ここの書き方チェック
      return view('insta/favoritesuser',["usersdata"=>$usersdata]);
    }

    public function profile(Request $request){
      //Tweetテーブルで対応する主キーをここで読み込んで、View側でforeach
      //いいね数カウント実装12/19
      $user_id = $request->input('user_id');
      $usertweet = Tweet::where('user_id',$user_id)->orderBy('created_at', 'desc')->get();//12/19クリックしたユーザの名前に対応するつぶやきを取得
      $username = User::where('user_id',$user_id)->value('username');
      $avatar = User::where('user_id',$user_id)->value('avatar');
      $count = Favorite::where('user_id',$user_id)->where('favorite',1)->count();//ここリレーションお試しポイント
      //$count = Tweet::where('user_id',$user_id)->favorites()->where('favorite',1)->count();//リレーションしたかったけどうまくいかない
      return view('insta/profile', ["usertweet" => $usertweet,"count"=>$count,"username"=>$username,"avatar"=>$avatar]);
    }

    //viewで指定するのはファイルの位置っぽい→Viewはinsta/〜にしましょう

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

      if ($request->file('file')->isValid([])) {//ファイルの中身があるなら
          $path = $request->file('file')->store('public');//ツイートされた画像を保存
          $filename = basename($path);//basename()→（）のいる場所を除いたパスデータ
          $caption = $request -> input('caption');//inputの使い方とか時間があれば細かく見たい、requestとセットで使うのでは
          $user = Auth::user();//Userテーブルから持ってくるとデータが汚いからログイン情報から読み込む、　"カラム名:〜"　みたいになる
          $user_id = $user->user_id;
          $username = $user->username;
          $avatar = $user->avatar;
          $now = date("Y/m/d H:i:s");//ツイートしたときの日付時刻データ、降順するのに使う
          $tweet_id = Tweet::create(['user_id'=>$user_id,'username'=>$username ,'avatar'=>$avatar, 'imagepath'=>$filename , 'caption'=>$caption ,'created_at'=>$now, 'updated_at'=>$now]);
          Favorite::create(['tweet_id'=>$tweet_id->tweet_id ,'user_id'=>$user_id,'favorite'=>null,'created_at'=>$now, 'updated_at'=>$now]);
        //名残  //$Tweet = Tweet::orderBy('tweet_id', 'desc')->get();//desc降順asc昇順、データとセットで使う
          //$Tweet = Tweet::simplePaginate(3);//simpleつけるとNextとPrevだけにできる（大量にあるときに有効）、こんな雑な１行でページ機能付与できるのすごい
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
      $delete_id = $request -> input('delete_id');//hiddenで持ってきた、name:delete_id、value:tweet_id
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
