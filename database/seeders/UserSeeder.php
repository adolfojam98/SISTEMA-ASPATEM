<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(true) {
            User::firstOrCreate(
                ['email' => 'systemaaspatem@gmail.com'], 
                [
                    'name' => 'Admin',
                    'password' => bcrypt('sysaspatem22')
                ]
            );
        }
    }
}
