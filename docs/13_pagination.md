# ページネーション
大量のレコードを有するテーブルを一覧表示する際、取得を分割する

## 利用方法

1. コントローラ側の記述
ページネーションしたいデータ取得のメソッドを`paginate(１ページ当たりの取得件数)` にする
```
/* Dog モデル(dogsテーブル)のレコードを5件に分けて取得する */
$records = App\Models\Dog::select('id', 'name', 'year')->paginate(5);
```

2. view 側の記述
リスト表示部分は、ページネーションしない場合と同じ(@foreach ディレクティブなどを使ってループ処理)  
ページングのリンクを表示したい部分で、`links()` メソッドを使う
```
@foeach($record as $record)
  {{-- 一覧表示のhtmlを記述 --}}
@endforeach

{{-- ページ切り替え用のリンクUIを出力 --}}
{{ $records->links() }}
```

## ページネーションの日本語化
出力される結果は、デフォルトではtailwindを用いたbladeファイルになっているので、これを編集する
artisan コマンドで、編集しやすいように、初期状態で`/vendor` 配下に保存されているbladeファイルを`/resources` 配下に複製する
```
# /vendor/laravel/framework/src/Illuminate/Pagination/resources/views から
# /resources/views/vendor/patination にファイルをコピーする

$ php artisan vendor:publish --tag=laravel-pagination
```
`resources` 配下に出力されたファイルを編集することで、`links()` メソッドで出力される内容をカスタマイズできる
