<?php

namespace Database\Seeders;

use App\Models\WalletSetup;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletSetup::insert([
            [
                'country_id' => 1,
                'code' => 'RW',
                'name' => 'Register Wallet',
                'is_allowed_admin' => 1,
                'is_allowed_member' => 1,
                'decimal_length' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'country_id' => 1,
                'code' => 'BW',
                'name' => 'Bonus Wallet',
                'is_allowed_admin' => 1,
                'is_allowed_member' => 1,
                'decimal_length' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
