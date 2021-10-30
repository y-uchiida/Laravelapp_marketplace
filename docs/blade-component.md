# Blade コンポーネント / コンポーネントクラス

## 利点
- Cotroller と Viewの分離
- コントローラーで、View表示のためのデータの取得、成形をしなくてよくなる
- コントローラーでBladeコンポーネントを呼び出して利用できるので、コードの再利用性が高まる

## Blade コンポーネント
- Bladeファイルにコンポーネントを表示する
- スロットの機能を利用して、コンポーネント内の内容を可変させる
- コンポーネント自体を変更すると、すべてに反映されるので、修正に強くなる

## 配置
`resources/views/components` ディレクトリ内に配置  
- `resources/views/components/component-nanme.blade.php` を読み込む: `<x-component-name></x-component-name>`
- `resources/views/components/subdir/component-nanme.blade.php` を読み込む: `<x-subdir.component-name></x-subdir.component-name>`

### x-slot は使えない
`resources/views/components/slot/sample.blade.php` を読み込もうとして、`<x-slot.component-name></x-slot.component-name>` とやったらエラーになった  
`x-slot` という並びがダメらしく、`x-slot_sample` はダメだけど `x-sample_slot` はOK

## 名前付きslot
上記がダメなのは、名前付きslotの機能とバッティングするから  
コンポーネント内に複数のスロットを持たせたいとき、どのスロットの内容に該当するかを明示するために`<x-slot name="スロット名">` というように記述する

## コントローラから変数を受け取る場合
`<x-component :名前="コントローラから受け取った変数名" />` で受け取れる  
アンダースコアを入れるとうまく使えないので避ける方がいい
コントローラで`$var_1`などを使っていたらundefined variable でうまく受け渡せなかった

## 各属性の初期値
`@props()` に連想配列で各項目の初期値を渡せる  
属性値だけでなく、名前付きslotの場合も可能

## コンポーネントクラスの作成
`artisan make:component ComponentClassSamle`  
以下のファイルが生成される
- `app/View/Components/ComponentClassSamle.php`: コンポーネントクラス、コンストラクタや描画前のデータ処理など
- `resources/views/components/component-class-samle.blade.php`: コンポーネントの表示内容
