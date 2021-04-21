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
        $user->profile()->create([
            'bio' => $this->faker->realText($maxNbChars = 150, $indexSize = 2),
            'resume' => ''
        ]);

        $title =  $this->faker->sentence;
        return [
            'title' => $title,
            'slug' => time() . '-' .Str::slug($title, '-'),
            'owner_id' => $user->id,
            'body' => $this->faker->realText($maxNbChars = 2000, $indexSize = 4),
            'published' => true,
            'thumb_img' => '',
            'cover_img' => ''
        ];
    }
}
