<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeIpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            ['name' => 'Office A', 'ip' => '192.168.1.100'],
            ['name' => 'Office B', 'ip' => '10.0.0.1'],
            ['name' => 'Office C', 'ip' => '172.16.0.10'],
            ['name' => 'Office D', 'ip' => '192.168.2.50'],
            ['name' => 'Office E', 'ip' => '10.1.1.100'],
            ['name' => 'Office F', 'ip' => '172.17.0.5'],
            ['name' => 'Office G', 'ip' => '192.168.3.25'],
            ['name' => 'Office H', 'ip' => '10.2.2.200'],
            ['name' => 'Office I', 'ip' => '172.18.0.15'],
            ['name' => 'Office J', 'ip' => '192.168.4.75'],
        ];

        DB::table('office_ip')->insert($data);
    }
}
