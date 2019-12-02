<!-- エラーメッセージ。なければ表示しない -->
@if ($errors->any())
<ul>
    @foreach($errors->all() as $error)<!-- 左から右を取り出す（errors関数に$errorがあるなら表示） -->
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif

<!-- フォーム -->
<form action="{{ url('upload') }}" method="POST" enctype="multipart/form-data">

    <!-- アップロードした画像。なければ表示しない -->
    <!--追加：foreach文でテーブルにある画像のパスを全部表示させる-->
    <!--imageテーブルから一つの画像パス（oneimage）を取り出し、それをfilenameに代入（関数だと文字じゃなくてデータだからURL表記に適さない）-->
    @isset ($image)
    @foreach($image as $oneimage)
    <div>
        <img src="{{ asset('storage/' . $oneimage->filename) }}">
    </div>
    @endforeach
    @endisset

    <label for="photo">画像ファイル:</label>
    <input type="file" class="form-control" name="file">
    <br>
    <hr>
    {{ csrf_field() }}
    <button class="btn btn-success"> Upload </button>
</form>
