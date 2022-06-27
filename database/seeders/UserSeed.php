<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'muraguchi',
        ]);
        User::create([
            'name' => 'katsuta',
        ]);
        User::create([
            'name' => 'mitsui',
        ]);
        User::create([
            'name' => 'terakawa',
        ]);
        User::create([
            'name' => 'kondo',
        ]);
    }
}
