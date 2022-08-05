<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectType;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectType::create([
            'name' => 'Стартап',
            'description' => 'Новый проект',
        ]);

        ProjectType::create([
            'name' => 'Проблема',
            'description' => 'Проект с ошибкой',
        ]);

        ProjectType::create([
            'name' => 'Задача',
            'description' => 'Проект с новыми задачами',
        ]);
    }
}
