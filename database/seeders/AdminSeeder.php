<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'surname' => 'Иванов',
            'name' => 'Иван',
            'patronymic' => 'Иванович',
            'email' => 'admin@gmail.com',
            'phone' => '+79505103456',
            'organization' => 'ЮГУ',
            'maximum_number_of_projects' => '999',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('admin', 'moderator', 'expert', 'curator');
    }
}
