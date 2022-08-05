<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(SubjectAreaSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(ProjectTypeSeeder::class);
        $this->call(CuratorSeeder::class);
        $this->call(ModeratorSeeder::class);
        $this->call(ExpertSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(RoleSubjectAreaSeeder::class);
        $this->call(UserSubjectAreaSeeder::class);
    }
}
