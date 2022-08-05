<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CuratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'surname' => 'Кузьмина',
            'name' => 'Алиса',
            'patronymic' => 'Григорьевна',
            'email' => 'curator1@gmail.com',
            'phone' => '+79905163456',
            'organization' => 'ЮГУ',
            'maximum_number_of_projects' => '10',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('curator');

        User::create([
            'surname' => 'Копылова',
            'name' => 'Майя',
            'patronymic' => 'Семёновна',
            'email' => 'curator2@gmail.com',
            'phone' => '+78805163456',
            'organization' => 'ЮГУ',
            'maximum_number_of_projects' => '10',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('curator');

        User::create([
            'surname' => 'Дмитриева',
            'name' => 'Александра',
            'patronymic' => 'Ильинична',
            'email' => 'curator3@gmail.com',
            'phone' => '+77775163456',
            'organization' => 'ЮГУ',
            'maximum_number_of_projects' => '10',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('curator', 'expert');
    }
}
