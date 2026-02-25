<?php

namespace Modules\Post\database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Post\Enums\PostStatus;
use Modules\Post\Enums\PostType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Post\Models\Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker = \Faker\Factory::create('id_ID');

        return [
            'name' => substr($this->faker->sentence(rand(6, 10)), 0, -1),
            'slug' => '',
            'intro' => $this->faker->paragraph,
            'content' => $this->faker->paragraphs(rand(5, 7), true),
            'type' => $this->faker->randomElement(PostType::getAllValues()),
            'is_featured' => $this->faker->randomElement([1, 0]),
            'image' => 'https://picsum.photos/1200/630?random='.rand(1, 100),
            'status' => $this->faker->randomElement(PostStatus::getAllValues()),
            'category_id' => $this->faker->numberBetween(1, 6),
            'meta_title' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'meta_og_image' => '',
            'meta_og_url' => '',
            'created_by_name' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'published_at' => Carbon::now(),
        ];
    }
}
