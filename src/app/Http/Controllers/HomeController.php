<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Model\Image;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
    * ファイルアップロード処理
    */
    public function upload(Request $request)
    {
      $this->validate($request, [
        'file' => [
            // 必須
            'required',
            // アップロードされたファイルであること
            'file',
            // 画像ファイルであること
            'image',
            // MIMEタイプを指定
            'mimes:jpeg,png',
        ]//これらは制約みたいなもん、validateメソッドの仕様、書き方
    ]);

    if ($request->file('file')->isValid([])) {
        $path = $request->file('file')->store('public');

          //return view('home')->with('filename', basename($path));元の一文
          $filename = basename($path);//basename()→（）のいる場所を除いたパスデータ
          image::insert(["filename" => $filename]); //imageテーブルの要素名filenameに＄falenameのデータを入れる
          $Image = image::all(); // テーブルから全データの取り出し（Viewで一つずつ取り出す操作を行う）
          return view('home', ["image" => $Image]);//View表示の返り値に取り出した全データをimageにいれてくっつける
    } else {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors();
    }
}
}
