<?php

namespace Jensramakers\LaravelMediaLibrary\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jensramakers\LaravelMediaLibrary\app\Models\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = File::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'file_category_id' => 1,
            'path' => 'https://picsum.photos/id/'.$this->faker->randomElement(array_diff(range(0, 200), [97])),
            'name' => '/200/300',
            'mime' => 'image/jpeg',
            'is_external' => true,
            'mime_icon' => 'fa-file-image',
        ];
    }
}