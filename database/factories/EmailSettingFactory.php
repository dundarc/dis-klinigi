<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailSetting>
 */
class EmailSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => 1, // Always use ID 1 for singleton
            'mailer' => 'smtp',
            'host' => $this->faker->domainName,
            'port' => 587,
            'username' => $this->faker->email,
            'password' => $this->faker->password,
            'encryption' => 'tls',
            'from_address' => $this->faker->email,
            'from_name' => $this->faker->name,
            'dkim_domain' => $this->faker->domainName,
            'dkim_selector' => 'default',
            'dkim_private_key' => null,
            'spf_record' => 'v=spf1 include:_spf.' . $this->faker->domainName . ' ~all',
        ];
    }
}
