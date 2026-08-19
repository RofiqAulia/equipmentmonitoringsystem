<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\RetrievalLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RetrievalLog>
 */
class RetrievalLogFactory extends Factory
{
    protected $model = RetrievalLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'user')->inRandomOrder()->first()?->id ?? User::factory(),
            'supervisor_id' => User::where('role', 'admin')->inRandomOrder()->first()?->id ?? User::factory(),
            'item_id' => Item::inRandomOrder()->first()?->id ?? Item::factory(),
            'quantity_picked' => rand(1, 5),
            'picked_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'notes' => $this->faker->sentence(),
        ];
    }
}
