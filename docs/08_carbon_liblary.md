# 日付ライブラリ Carbon
PHPの拡張日付ライブラリ  
比較、加減算、時間の変更など様々な機能が搭載され柔軟に使える  
Laravelでは標準で搭載されており、Eloquent でデータベースから取得した日付はCarbonのインスタンスになっている  
(クエリビルダを使う場合はCarbonではない)

## 利用方法

1. コントローラやSeederなどで使うとき
```
/* ライブラリを読み込み */
use Carbon\Carbon; 

/* 現在時刻を出力 */
print(Carbon::now());
```

2. Viewファイルで使うとき
```
{{-- 現在時刻を出力 --}}
{{Carbon\Carbon::now()}}

{{-- 指定の日付を表す場合の例 --}}
{{-- parse() で日時文字列を読み込んでformat() で指定の形式に出力 --}}
{{ Carbon\Carbon::parse('2000-12-31 10:20:30')->format('Y/m/d, h:i:s') }}

```

## Carbonの便利メソッド
- diffForHumans()
  その日付が、「今の時刻からどれだけ時間がたったか」の形式で表示してくれる(●秒前とか、●か月前など)

## 参考
- docs for carbon
  https://carbon.nesbot.com/docs/
