# リレーションと外部キー制約

## リレーションとは
レコード同士のつながりを表現するためのもの  
関連する別のテーブルの主キー(id)に対してJOIN クエリでレコードをつなげる  

## 外部キー制約
外部キー関係を通じてデータベース一貫性を維持するタイプの制約  
関連するテーブルに変化が起きたとき、指定された制約の種類によってデータが変更される  
https://dev.mysql.com/doc/refman/5.6/ja/glossary.html#glos_foreign_key_constraint


## マイグレーションファイルでリレーションと外部キー制約を設定する
```
# ownersテーブルのidカラムの値を保持する場合(主キーがidという名前だったらという前提で)
$table->foreignId('owner_id')->constrained();
```

上記の内容でマイグレーションを実行すると、Unsignedのbigint型としてdog_idが作られ、  
さらにBTREEタイプのインデックスが作成される（「dogs_owner_id_foreign」）

## マイグレーションファイルによるカスケード設定
外部キー制約を設定している状態で主テーブルのレコードを削除すると、外部キー制約のためにエラーが発生する  
制約の一貫性が保てるように、従テーブルの関連するレコードも一緒に変更を加える`cascade` という設定を追加する
```
/* cascade設定は、constrained()に続けて書くことができる */
$table->foreignId('owner_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

/* すでに制約を設定している場合は、いったん制約を外してから付け直す */
$table->dropForeign('dogs_owner_id_foreign');
$table->foreign('owner_id')->references('id')->on('owners')->onUpdate('cascade')->onDelete('cascade');
```

## 外部キーが数値ではない場合の対応
もし、外部キーがunsigned big integer 型ではない場合（文字列を組み合わせてUUIDなどを作っているようなとき）は、  
カラムの作成と制約の設定を別々に行う必要がある
```
/* 型を指定してカラムを作成 */
$table->uuid('owner_id');;
/* 外部キー制約を設定 */
$table->foreign('owner_id')->references('id')->on('owners');
```

## 参考
マイグレーション 8.x Laravel:
https://readouble.com/laravel/8.x/ja/migrations.html#foreign-key-constraints

参照アクション(13.1.17.2 外部キー制約の使用) MySQL リファレンスマニュアル:
https://dev.mysql.com/doc/refman/5.6/ja/create-table-foreign-keys.html

Database: Migrations (Laravel docs):
https://laravel.com/docs/8.x/migrations#dropping-foreign-keys
