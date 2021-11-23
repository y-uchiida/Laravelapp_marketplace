# ダミーデータの生成(Seeding)

シーダーファイルを使ってデータベースにレコードを追加することができる

## seeder ファイルの生成
```
$ php artisan make:seeder ***TableSeeder
```
シーダーファイルには特に命名規則はないが、初期データの挿入の場合は「***TableSeeder」などにすることが多い

## seeder ファイルの編集
run() メソッド内に、データベースへのレコード書き込み処理を記述する  
DB ファサードやモデルを使って手動で差し込みすることもできるし、ランダムデータを作成するためのしくみ(Faker と factroy)を利用して  
大量にデータを流し込みすることもできる

## シーディングの実行
シーディング実行のエントリーポイントとして、`DatabaseSeeder.php` が用意されている  
このクラスの `run()`メソッド内から、`call()` でSeederクラスを呼び出す   
```
// database/seeders/DatabaseSeeder.php
public function run(){
    this->call([
        UsersTableSeeder::class,
        DogsTableSeeder::class,
        CatsTableSeeder::class
    ]);
}
```

DatabaseSeeder.php は、以下のartisan コマンドで実行できる
```
$ php artisan db:seed
```
いくつかのテーブルに初期データをまとめて登録したい場合はこの方法が便利

## シーディングの実行2: 特定のシーダーファイルを実行する
`DatabaseSeeder.php` のrun() メソッドを変更せずに特定のファイルのシーディングだけを実行したい場合
```
# '--class' オプションで、実行したいファイルを指定できる
$ php artisan db:seed --class=***Seeder
```

## シーディングの実行3: テーブルの再作成も行う場合
マイグレーションのコマンドに、`--seed` オプションをつけるとシーディングも実行することができる
```
# 各Seederクラスのdown() を実行してテーブルの定義を巻き戻した後、up() を実行する
$ php artisan migrate:refresh --seed

# すべてのテーブルを削除して、空のデータベースに対してup() を実行する
$ php artisan migrate:fresh --seed
```

## 参考資料
- データベース：シーディング 8.x Laravel:
  https://readouble.com/laravel/8.x/ja/seeding.html
