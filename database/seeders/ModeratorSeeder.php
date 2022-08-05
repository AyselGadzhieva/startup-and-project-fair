<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class ModeratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'surname' => 'Шестаков',
            'name' => 'Тимофей',
            'patronymic' => 'Константинович',
            'email' => 'moderator1@gmail.com',
            'phone' => '+78885103456',
            'organization' => 'ЮГУ',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('moderator');

        User::create([
            'surname' => 'Мартынова',
            'name' => 'Аделина',
            'patronymic' => 'Кирилловна',
            'email' => 'moderator2@gmail.com',
            'phone' => '+78090903456',
            'organization' => 'ЮГУ',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('moderator');

        User::create([
            'surname' => 'Лазарев',
            'name' => 'Артём',
            'patronymic' => 'Семёнович',
            'email' => 'moderator3@gmail.com',
            'phone' => '+78885107086',
            'organization' => 'ЮГУ',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('moderator');
    }
}
