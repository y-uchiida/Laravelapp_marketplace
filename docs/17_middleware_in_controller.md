# コントローラのコンストラクタ内で、カスタムミドルウェアを定義して実行させる
コントローラのコンストラクタは、アクションメソッドが開始される前の共通処理として利用できる  
そのため、ここでミドルウェアを呼び出すと、そのコントローラにだけ特定のミドルウェアの処理を追加することができる

`middleware()` メソッドは、その引数にクロージャ(無名関数)を受け取って、この関数をミドルウェアとして実行することができる  

```
/* コントローラのコンストラクタ */
public function __construct()
{
    $this->middlewate(function($request, $next){
        /* 独自のミドルウェアとして処理を記述 */
        /* ... */

        /* ミドルウェアなので、$next($request) を returnする */
        return ($next($request));
    });
}
```
