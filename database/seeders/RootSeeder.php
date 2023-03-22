<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tree')->insert([
            'id'        => 1,
            'parent_id' => 0,
            'position'  => 0,
            'path'      => 1,
            'level'     => 1
        ]);
    }
}
