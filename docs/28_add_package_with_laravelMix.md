# Laravel Mixの設定からnode moduleを追加する
今回は、画像スライダーのパッケージ swiper.js を導入する

1. npm install でパッケージを導入
```bash
# 動画講義の内容に合わせるため、バージョンを指定
$ npm install swiper@6.7.0
```

2. resource に関連ファイルを作成
- `resource/js/swiper.js` を作成、公式サイトを参考に記述
```JavaScript
 // import Swiper JS
 import Swiper from 'swiper';
 // import Swiper styles
 import 'swiper/swiper-bundle.css';

// core version + navigation, pagination modules:
import SwiperCore, { Navigation, Pagination } from 'swiper/core';

// configure Swiper to use modules
SwiperCore.use([Navigation, Pagination]);

// init Swiper:
const swiper = new Swiper('.swiper-container', {
  // Optional parameters
  // direction: 'vertical',
  loop: true,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  // And if we need scrollbar
  scrollbar: {
    el: '.swiper-scrollbar',
  },
});
```

- `resource/css/swiper.css` を作成、レイアウト用のcss スタイルを記述
```css
.swiper-container {
  /* width: 600px; */
  height: 300px;
}
```

- 作成したcssのimport 設定を、`app.css` に追記する
```css
@import 'swiper';
```

3. Laravel Mix へ、インポート設定を追記
`webpack.mix.js` へ記述
```JavaScript
/* webpack.mix.js */
mix.js('resources/js/app.js', 'public/js') /* app.js を読み込み */
    .js('resources/js/swiper.js', 'public/js') /* コンパイル対象に swiper.js を追加 */
    .postCss('resources/css/app.css', 'public/css', [
    /* ... (以下略) ... */
```

4. npm コンパイルを実行
```bash
$ npm run dev
```

5. viewでswiperを記述
- 公式サイトを参考にして、スライダーHTMLを記述

- `asset()` ヘルパ関数を使って、画像を読み込みする

- `mix()` ヘルパ関数を使って、swiper のJavaScript ソースを読み込む
```html
    {{-- mix() ヘルパ関数で、js/swiper.js を追加ロード --}}
    <script src="{{ mix('js/swiper.js')}}"></script>
</x-app-layout>
```

## ヘルパ関数 mix()  
`app.js` はすべてのページでロードされるが、swiperなど容量の大きいパッケージを含めてしまうと、サイト全体のレスポンスに影響が出る  
そこで、容量が大きいパッケージはコンパイルされるファイルを分けておき、それを利用するページで `mix()` ヘルパを利用して  
リソースを追加ロードすることで  不要なトラフィックを発生させずに済む
