<?php

namespace Database\Factories;

use App\Models\Common\ApplicationModel as Application;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       $data = file_get_contents(__DIR__.'/../backup/application.json');
       $data = json_decode($data);
       return $data; 
    }
}
