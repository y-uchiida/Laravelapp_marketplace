# バリデーションで外部キーの存在チェック
`exists:テーブル名,キー名` で、フォームの値が存在しているか確認できる
フォームから送信されたuser_id の値が、users テーブルの id に存在しているかをチェックする例:
```
'user_id' => 'required|exists:users,id'
```
