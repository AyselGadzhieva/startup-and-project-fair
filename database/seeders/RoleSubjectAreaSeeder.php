<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleSubjectArea;


class RoleSubjectAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoleSubjectArea::create([
            'name' => 'Аналитик данных',
            'subject_area_id' => '1',
        ]);

        RoleSubjectArea::create([
            'name' => 'Экономист',
            'subject_area_id' => '1',
        ]);

        RoleSubjectArea::create([
            'name' => 'Графический дизайнер',
            'subject_area_id' => '2',
        ]);

        RoleSubjectArea::create([
            'name' => '3D-аниматор',
            'subject_area_id' => '2',
        ]);

        RoleSubjectArea::create([
            'name' => '3D-риггер',
            'subject_area_id' => '2',
        ]);

        RoleSubjectArea::create([
            'name' => 'Backend-разработчик',
            'subject_area_id' => '3',
        ]);

        RoleSubjectArea::create([
            'name' => 'Frontend-разработчик',
            'subject_area_id' => '3',
        ]);

//        RoleSubjectArea::create([
//            'name' => 'Химик-исследователь',
//            'subject_area_id' => '4',
//        ]);
//
//        RoleSubjectArea::create([
//            'name' => 'Лаборант химического анализа',
//            'subject_area_id' => '4',
//        ]);
//
//        RoleSubjectArea::create([
//            'name' => 'Астроном',
//            'subject_area_id' => '5',
//        ]);
//
//        RoleSubjectArea::create([
//            'name' => 'Инженер-оптик',
//            'subject_area_id' => '5',
//        ]);
    }
}
