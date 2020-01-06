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

    // ログアウト追加12/11、なんか反応しない(元から)12/19、postLogoutという名前に変えてたりとデフォルトから設定がズレてただけというオチ
    public function logout()
    {
      Auth::logout();//最有力
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
     public function handleProviderCallback(Request $request)//上のメソッドのredirectでここにくる、config/service参照
     {
         $github_user = Socialite::driver('github')->user();//'github'から送られたユーザ情報を取得
         $github_avatar = $github_user->avatar;
         $instausers=User::firstOrCreate(['username'=>$github_user->user['login'],'avatar'=>$github_avatar]);//あったら取り出す、なきゃつくる
         auth()->login($instausers);//ログイン
         return redirect('/home');
         //viewだと引数渡せる？けど直接ファイルを開く操作だからCSSが読み込まれないぽい
     }
}
