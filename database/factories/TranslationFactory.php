<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug,
            'locale' => $this->faker->randomElement(['en', 'fr', 'es']),
            'value' => $this->faker->sentence,
            'tags' => ['web', 'mobile'],
        ];
    }

}
