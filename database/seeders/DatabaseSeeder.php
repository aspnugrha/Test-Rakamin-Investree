<?php

namespace Database\Seeders;

use App\Models\User;
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
        // \App\Models\User::factory(10)->create();

        $names = ['asep', 'fadil', 'donny', 'sahril'];

        foreach ($names as $name) {
            User::insert([
                'name'  => $name,
                'email' => $name . '@gmail.com',
                'password'  => bcrypt($name),
            ]);
        }
    }
}
