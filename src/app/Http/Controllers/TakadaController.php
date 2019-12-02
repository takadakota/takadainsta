<?php
namespace App\Http\Controllers;
use App\User;
class TakadaController extends Controller
{
    public function index()
    {
        // データの追加 emailの値はランダムな文字列を使用。「.」で文字列の連結
        $email = substr(str_shuffle('tkdaaa'), 0, 6) . '@god.com';
        User::insert(['name' => 'takada kota', 'email' => $email, 'password' => 'xxxxxxxx']);
        // 全データの取り出し
        $users = User::all();
        return view('user', ['users' => $users]);
    }
}
