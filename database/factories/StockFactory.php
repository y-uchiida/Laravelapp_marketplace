<?php

namespace Database\Factories;

use App\Models\Product; /* Product のFactoryを利用するので、こちらのModelも読み込んでおく */
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /* product のidを持たなければいけないので、Product::factory()を利用してレコードを作る */
        $product = Product::factory(1)->create();
        return [
            'product_id' => $product[0]->id,
            'type' => $this->faker->numberBetween(1, 2),
            'quantity' => $this->faker->randomNumber,
        ];
    }
}
