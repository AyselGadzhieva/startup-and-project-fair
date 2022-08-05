<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubjectArea;

class SubjectAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubjectArea::create(['name' => 'Математика']);
        SubjectArea::create(['name' => 'VR']);
        SubjectArea::create(['name' => 'Информационные технологии']);
//        SubjectArea::create(['name' => 'Химия']);
//        SubjectArea::create(['name' => 'Физика']);
    }
}
