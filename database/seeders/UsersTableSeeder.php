<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //administrator user
        $user = new User();
        $user->name = 'Super User';
        $user->username = 'admin';
        $user->email = 'admin@admin.com';
        $user->password = bcrypt('password');
        $user->save();
    }
}