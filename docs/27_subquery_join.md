# クエリビルダで、サブクエリを含む複雑なSQLを実行する
DB ファサードの`table()` で対象のテーブルを指定する  
SQLの命令文は、クエリビルダのメソッドになっているので、それらをメソッドチェインでつなげていく  
メソッドがないものについては、`DB::raw(SQL文)` と記述することで、SQL文をそのままクエリビルダに読み込ませることができる  
ItemControllerのindexアクションでは、現在販売可能な商品の一覧を取得している  
現在販売可能とは、
- 在庫数が1つ以上存在している
- その商品(product) のステータスが販売中(is_selling === true) である
- その商品を扱っている店舗(shop)のステータスが販売中(is_selling === true) である
の3つの条件を満たしているもの  
このような複雑な条件に基づいてレコードを取り出す場合、SQL文をそのまま利用するほうが便利な場合がある
実際の内容は、ItemController内にコメントとして記述している
