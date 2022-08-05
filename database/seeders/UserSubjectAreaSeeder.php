<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserSubjectAreas;

class UserSubjectAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserSubjectAreas::create([
            'user_id' => 1,
            'subject_area_id' => 3,
            'role_id' => 3
        ]);

        UserSubjectAreas::create([
            'user_id' => 2,
            'subject_area_id' => 1,
            'role_id' => 4
        ]);

        UserSubjectAreas::create([
            'user_id' => 3,
            'subject_area_id' => 2,
            'role_id' => 4
        ]);

        UserSubjectAreas::create([
            'user_id' => 4,
            'subject_area_id' => 3,
            'role_id' => 4
        ]);

        UserSubjectAreas::create([
            'user_id' => 4,
            'subject_area_id' => 1,
            'role_id' => 4
        ]);

        UserSubjectAreas::create([
            'user_id' => 4,
            'subject_area_id' => 2,
            'role_id' => 3
        ]);

        UserSubjectAreas::create([
            'user_id' => 8,
            'subject_area_id' => 1,
            'role_id' => 3
        ]);

        UserSubjectAreas::create([
            'user_id' => 9,
            'subject_area_id' => 3,
            'role_id' => 3
        ]);

        UserSubjectAreas::create([
            'user_id' => 10,
            'subject_area_id' => 3,
            'role_id' => 4
        ]);

        UserSubjectAreas::create([
            'user_id' => 10,
            'subject_area_id' => 1,
            'role_id' => 4
        ]);

    }
}
