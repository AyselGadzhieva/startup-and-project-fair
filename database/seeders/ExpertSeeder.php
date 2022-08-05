<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class ExpertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'surname' => 'Зверева',
            'name' => 'Елизавета',
            'patronymic' => 'Платоновна',
            'email' => 'expert1@gmail.com',
            'phone' => '+71105103456',
            'organization' => 'ЮГУ',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('expert');

        User::create([
            'surname' => 'Васильев',
            'name' => 'Эмин',
            'patronymic' => 'Даниэльевич',
            'email' => 'expert2@gmail.com',
            'phone' => '+71105993456',
            'organization' => 'ЮГУ',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('expert');

        User::create([
            'surname' => 'Журавлев',
            'name' => 'Илья',
            'patronymic' => 'Ильич',
            'email' => 'expert3@gmail.com',
            'phone' => '+71445993456',
            'organization' => 'ЮГУ',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('expert', 'curator');
    }
}
