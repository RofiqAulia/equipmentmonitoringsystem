<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = Str::upper(Str::random(6));

        return [
            'sku' => 'SKU-'.$code,
            'qr_code_payload' => 'QR-'.$code.'-'.rand(100, 999),
            'name' => $this->faker->words(3, true),
            'location_bin' => 'Gudang '.chr(rand(65, 70)).'/'.rand(1, 20),
            'available_stock' => rand(10, 100),
            'minimum_stock' => 5,
            'image_url' => 'https://placehold.co/300x300/1e293b/06b6d4?text='.urlencode('Item '.$code),
        ];
    }
}
