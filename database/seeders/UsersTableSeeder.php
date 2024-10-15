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
        $user->username = 'E00000';
        $user->email = 'admin@admin.com';
        $user->password = bcrypt('password');
        $user->first_name = 'Admin';
        $user->title = 'Mr.';
        $user->cid_no = '11211000920';
        $user->employee_id = 0;
        $user->gender = 1;
        $user->dob = '1995-02-13';
        $user->birth_place = 'Samchi';
        $user->birth_country = 'Bhutan';
        $user->marital_status = 1;
        $user->contact_number = 1799619;
        $user->nationality = 'Bhutanese';
        $user->date_of_appointment = '2018-02-13';
        $user->cid_copy = 'aa/bb/c';
        $user->save();

        
    }
}