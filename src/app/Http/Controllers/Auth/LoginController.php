<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Auth\Authenticatable;//追加。こいつがログイン認証に関係してる?
use Socialite;// 追加！
use Illuminate\Http\Request;// 追加！
use Illuminate\Support\Facades\Auth;
use App\User;//DB直いじりをモデルにしたい12/11
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()//この子ちょっとよくわかんない
    {
          //$this->middleware('auth');
          $this->middleware('guest')->except('logout');//このコントローラ内でguestがアクションするとlogoutに飛ばす
    }

    // ログアウト追加12/11、なんか反応しない(元から)12/19
    public function logout()
    {
      //auth()->logout();
      Auth::logout();//最有力
      //$user=User::where('user_id',Auth::id())->get();
      //auth()->logout($user);//logout周りしっかりやらないとダメ、最後にやるでよさそう12/19
      //return route('logout')
      return redirect('/home');
    }

    /**
     * GitHubの認証ページヘユーザーをリダイレクト
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()// 追加！
    {
        return Socialite::driver('github')->scopes(['read:user', 'public_repo'])->redirect();
    }//githubに接続する、権限の範囲はscope内
    /**
     * GitHubからユーザー情報を取得
     *
     * @return \Illuminate\Http\Response
     */
     public function handleProviderCallback(Request $request)//どうやら上のメソッドで許可OKってなるとここに戻ってくるらしい
     {
         $github_user = Socialite::driver('github')->user();//'github'から送られたユーザ情報を取得
         $github_avatar = $github_user->avatar;
         $instausers=User::firstOrCreate(['username'=>$github_user->user['login'],'avatar'=>$github_avatar]);//あったら取り出す、なきゃつくる
         //$request->session()->put('github_token', $github_user->token);//この書き方は「sessionへデータを保存するという意味」put(1,2)で１に２を保存
         //sessionがAuthの機能？
         auth()->login($instausers);//ログイン
         return redirect('/home');
         //viewだと引数渡せる？けど直接ファイルを開く操作だからCSSが読み込まれない
     }
}
