<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
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
        $user = User::factory()->create();
        $title =  $this->faker->sentence;
        return [
            'title' => $title,
            'slug' => Str::slug($title, '-'),
            'owner_id' => $user->id,
            'body' => $this->faker->sentence(20),
            'published' => true
        ];
    }
}
