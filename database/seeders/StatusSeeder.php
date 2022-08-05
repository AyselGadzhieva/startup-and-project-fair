<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create(['name' => 'Черновик']);
        Status::create(['name' => 'Отправлена заявка']);
        Status::create(['name' => 'В обработке']);
        Status::create(['name' => 'На экспертизе']);
        Status::create(['name' => 'Оценен']);
        Status::create(['name' => 'Отправлен кураторам']);
        Status::create(['name' => 'Набор участников']);
        Status::create(['name' => 'В процессе']);
        Status::create(['name' => 'В архиве']);
        Status::create(['name' => 'Завершён']);
        Status::create(['name' => 'Отклонён']);
        Status::create(['name' => 'Кураторы определены']);
        Status::create(['name' => 'Команда собрана']);
    }
}
