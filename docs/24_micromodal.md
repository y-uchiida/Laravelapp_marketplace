# 軽量モーダルライブラリ micromodal.js

公式サイト:  
https://micromodal.vercel.app/

## インストール
1. npm でインストールする
```
$ npm install micromodal --save
$ npm run
```
  
2. import設定
`resources/js/bootstrap.js` に設定を追加
```
import MicroModal from 'micromodal';
MicroModal.init();
```


## 画面表示用のテンプレートの導入
githubにテンプレートが公開されている  
https://gist.github.com/ghosh/4f94cf497d7090359a5c9f81caf60699

これを`resources/css/micromodal.css` として保存  
`app.css` でimportを記述しておく
```
@import 'micromodal';
```
