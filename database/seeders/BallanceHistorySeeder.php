<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\BallanceHistory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BallanceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BallanceHistory::create([
            'user_id'=>1,
            'user_type'=>Doctor::class,
            'amount'=>100,
            'operation_type'=>'deposit'
        ]);
        BallanceHistory::create([
            'user_id'=>1,
            'user_type'=>Doctor::class,
            'amount'=>30,
            'operation_type'=>'withdraw'
        ]);
        BallanceHistory::create([
            'user_id'=>1,
            'user_type'=>Doctor::class,
            'amount'=>50,
            'operation_type'=>'revenu_from_consult',
            'consult_id'=>1
        ]);
    }
}
