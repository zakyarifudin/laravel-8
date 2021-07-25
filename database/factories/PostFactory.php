<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_post'   => Str::uuid(),
            'id_user'   => null,
            'title'     => $this->faker->sentence(3),
            'body'      => $this->faker->paragraph(3),
        ];
    }
}
