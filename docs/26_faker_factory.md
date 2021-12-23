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


## Factory ファイルの生成
Factoryは、指定の形式のファイルの内容をartisanから実行してデータを生成する  
```sh
# Product テーブルのダミーデータを生成するためのFactoryファイルを作成する例
$ artisan make:factory ProductFactory --model=Product
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

### Faker で利用できるプロパティ
