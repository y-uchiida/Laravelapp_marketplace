# ファイルの複数アップロードとバリデーション

## フォームからの送信方法
form側で、multiple属性付きのinputタグを使う
```
<input type="file" name="files[][image]" multiple ... />
```

## コントローラでの受け取り方
inputタグのname属性値をキーとして、配列形式で保存されている
```
$request->file('files')[0]
$request->file('files')[1]
$request->file('files')[2]
```

## バリデーション条件の記述方法
配列の番号になる部分を、`.*.` で区切って配列を表現する
```
/* <input type="file" name="files[][image]" > からアップロードされたファイルに対してバリデーションを設定する場合 */
return [
    'files.*.image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
];
```

