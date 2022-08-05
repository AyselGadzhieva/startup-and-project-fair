<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'surname' => 'Алексеев',
            'name' => 'Алексей',
            'patronymic' => 'Алексеевич',
            'email' => 'student1@gmail.com',
            'phone' => '+79505103456',
            'organization' => 'ЮГУ',
            'course' => '1',
            'group' => '1181б',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('student');

        User::create([
            'surname' => 'Соколов',
            'name' => 'Никита',
            'patronymic' => 'Дмитриевич',
            'email' => 'student2@gmail.com',
            'phone' => '+79505112906',
            'organization' => 'ЮГУ',
            'course' => '2',
            'group' => '1182б',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('student');

        User::create([
            'surname' => 'Герасимова',
            'name' => 'Пелагея',
            'patronymic' => 'Данииловна',
            'email' => 'student3@gmail.com',
            'phone' => '+79501100456',
            'organization' => 'ЮГУ',
            'course' => '3',
            'group' => '1183б',
            'password' => '$2y$10$.b0ySmDBU7iKwG44QwK6xuY7LitXpB582Q/Dmv7R9ltiTpviLcc0i', // password
        ])->assignRole('student');
    }
}
