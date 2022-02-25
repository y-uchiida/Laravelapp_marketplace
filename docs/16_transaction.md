# トランザクション

## トランザクション設定
DB ファサードに、トランザクション用のメソッドがあるので、それを使う

1. クロージャを使う場合
`DB::transaction()` が、トランザクションの発行からコミット、エラー時のロールバックまで自動でやってくれる  
細かい処理が必要なければこちらでよい  
クロージャ内に外部から変数を入れたい場合は、`use()` メソッドを使う
```
/* formに入力された内容を含んだ$request を
 * クロージャ内で使いたい場合は、use($request) で渡す必要あり
 */
DB::transaction(function() use($request) {
  DB::table('***')->create($request->input);
});
```

デッドロック発生時の再試行回数は、`DB::transaction()` の第二引数で指定できる  
試行回数を超過したら、例外を投げる
```
DB::transaction(function(){....}, 5); /* デッドロックに対して5回まで処理を再試行 */
```

2. 別々のメソッドを使う場合
`DB::beginTransaction()` でトランザクションを開始  
`DB::rollBack()` でロールバックを実行
`DB::commit()` で変更を確定  
トランザクションの中で特別な処理を細かく行う必要がある場合はこちらを利用する

## データ保存に失敗した場合の対応
DBファサードのトランザクションメソッドは例外を投げるので、try-catch構文で例外処理を書く
```
try{
    DB::transaction(function() use($request){
        /* データベースを操作する処理 */
    }, 5);
}catch(Throwable $e){
    /* Log ファサードで、エラーログを記録し、例外を投げなおす */
    Log::error($e);
    throw $e;
}
```

## 参考
データベーストランザクション 8.x Laravel:
https://readouble.com/laravel/8.x/ja/database.htm#database-transactions
