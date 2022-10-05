<?php

namespace Database\Seeders;

use App\Models\SysGeneral;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SysGeneral::insert([
            [
                'country_id' => 1,
                'type' => 'gender',
                'code' => 'male',
                'name' => 'Male',
                'seq_no' => 0
            ], [
                'country_id' => 1,
                'type' => 'gender',
                'code' => 'female',
                'name' => 'Female',
                'seq_no' => 10
            ]
        ]);
    }
}
