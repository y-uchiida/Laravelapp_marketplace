# artisan Tinker でデータベースに接続する
`artisan tinker` コマンドでpsy シェルを起動し、そこからLaravelが接続しているデータベースにアクセスできる  
現在のレコードの内容をコマンドライン上で確認することができてデバッグなどに便利

```
$ php artisan tinker
Psy Shell v0.10.9 (PHP 8.0.12 — cli) by Justin Hileman
>>> App\Models\Owner::find(1)
=> App\Models\Owner {#4564
     id: 1,
     name: "owner_dummy000",
     email: "owner_dummy000@example.com",
     email_verified_at: "2021-11-25 21:43:12",
     #password: "$2y$10$RvWllHQVjIldTDb9No3LzOwccj588OaDd6HkOLYNMpozrlWHtub/y",
     #remember_token: null,
     created_at: "2021-11-25 21:43:12",
     updated_at: null,
     deleted_at: null,
   }
>>>
```
終了時は`quit` またはCtrl + c
