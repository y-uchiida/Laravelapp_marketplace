# サービスクラス
複数のコントローラやモデルをまたがって共通な処理を行う際、それぞれの場所に記述すると  
肥大化して見通しが悪くなるし、保守性も下がる(全部直さないといけないので)
そういった場合は、処理をサービスクラスとして切り離しておき、必要な場所でそれを読み込んで利用する

## Serviceクラスを作成する
特に命名規約はないが、`app/Services` ディレクトリを作成して、この中にクラスファイルを保存することが多い  
Laravelのネームスペース規約に沿って、namespace を設定しておく
```
<?php
namespace App\Services;

class SomeService
{
    public statid function doSomething()
    {
        /* 共通化された処理 */
    }
}
```

## コントローラ側での呼び出し
切り出したクラスが、Laravelの別のインターフェースに依存しない場合は、サービスプロバイダへ登録しておく必要はない  
> Tip!! クラスがどのインターフェイスにも依存しない場合、クラスをコンテナーにバインドする必要はありません。
> コンテナは、リフレクションを使用してこれらのオブジェクトを自動的に解決できるため、これらのオブジェクトの作成方法を指示する必要はありません。
> 引用: Laravel ドキュメント

コントローラ側で、use して利用するだけでも問題なし
```
<?php
namespace App\Http\Controllers\SomeController

use App\Services\SomeService
...
    SomeService::doSomething(); /* アクションメソッド内で実行 */
```

## 参考
- Laravel でサービス(Service)クラスの作り方:
  https://qiita.com/ntm718/items/14751e6d52b4bebde810

- サービスコンテナ 8.x Laravel
  https://readouble.com/laravel/8.x/ja/container.html
