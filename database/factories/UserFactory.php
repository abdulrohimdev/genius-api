<?php

namespace Database\Factories;

use App\Models\Common\UserModel as User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'api_key' => Str::random(16),
            'secret_key' => Str::random(50),
            'device_id' => '',
            'device_name' => '',
            'username' => $this->faker->userName,
            'password' => Hash::make('developer'),
            'fullname' => 'Abdul Rohim',
            'auth_profile' => 'ess',
            'email' => 'abdulrohim34@gmail.com',
            'phone' => '082113460348',
            'locked' => 'No',
            'company_code' => '',
            'employee_id' => '',
            'language' => 'en',
            ];
    }
}
