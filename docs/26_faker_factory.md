# Faker, Factory でダミーデータ生成

## Faker
PHP の代表的なダミーデータ生成ライブラリ  

### Fakerの日本語化
`config/app.php` の`faker_locale`を変更  
ねんのためartisanのキャッシュをクリアしておく
```sh
$ php artisan config:clear
```

## Factory
Laravel の機能で、モデルのスキーマ定義に合わせてダミーデータを生成できる  

### Factory ファイルの生成
Factoryは、指定の形式のファイルの内容をartisanから実行してデータを生成する  
```sh
# Product テーブルのダミーデータを生成するためのFactoryファイルを作成する例
$ artisan make:factory ProductFactory --model=Product
```

## Factory を使ってデータ生成をするモデルは、Model ファイル側にも設定が必要
モデルファイル側に`use HaasFactory`をつけることで、モデルファイルから該当するFactoryを参照できるようになる  
```PHP
/* app/Models/Product.php */

/* HasFactoryを読み込み */
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    /* Product::factory()を利用可能にするため、 モデルにHasFactoryを入れておく必要がある */
    use HasFactory;

    /* ... 以下略 ... */

```

### Factory ファイルのフォーマット
Laravelの初期プロジェクトに、サンプルとしてusers テーブル用のFactory ファイルがあるので、それを参考にすると良い
```PHP

/* namespace にFactory を指定 */
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/* Factoryを継承したクラスをつくる */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    /* definition で生成するカラムの名前と、その形式をFaker のプロパティを使って設定する */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
```

## Factory でのダミーデータ生成を実行
Seederと同じ方法で実行する  
`database/seeders/DatabaseSeeder.php` の`run()` メソッドから、任意のFactoryを呼び出すように記述する
```PHP
/* DatabaseSeeder.php */

/* モデルを利用するので、use で読み込んでおく */
use App\Models\Product;

public function run(){
    $this->call([
        /* callは、ほかのSeederの呼び出しを記述 */
    ]);
    
    /* Product モデルのレコードを、Factoryを使って100件生成する場合  */
    Product::factory(100)->create();
}
```

## 余談
動画講座の内容に沿ってFactoryの内容を記述したら、外部成約のエラーが出てしまった。
制約付きのカラムの値の範囲をハードコーディングしていて、自分で以前に作ったSeederの内容とそぐわなかったのが原因  
(動画講義のFactoryは、事前に親テーブルのレコードを6件登録している前提でidの範囲を1~6にしていたのに、こちらでは4件しか登録していなくて範囲外になるなど)  
制約付きのテーブル設計に慣れていないこともあって、原因特定と解消に結構時間をかけてしまった
自動生成の場合、全体の整合性をきちんと見ていくようにしたい…

## 参考
### Faker で利用できるプロパティ
[Laravel5.1]Fakerチートシート: 日本語の結果サンプルも付いていて便利  
https://qiita.com/tosite0345/items/1d47961947a6770053af
