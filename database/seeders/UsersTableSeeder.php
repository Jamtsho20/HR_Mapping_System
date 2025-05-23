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
       \DB::table('mas_employees')->insert([
    
        'name' => 'Super User',
        'username' => 'E00000',
        'email' => 'admin@admin.com',
        'password' => bcrypt('Tipl@2025'),
        'first_name' => 'Admin',
        'title' => 'Mr.',
        'cid_no' => '11211000920',
        'employee_id' => 0,
        'gender' => 1,
        'dob' => '1995-02-13',
        'birth_place' => 'Samchi',
        'birth_country' => 'Bhutan',
        'marital_status' => 1,
        'contact_number' => 1799619,
        'nationality' => 'Bhutanese',
        'date_of_appointment' => '2018-02-13',
        'cid_copy' => 'aa/bb/c',
        'created_by' => 1, // Assuming this is the user ID for 'created_by'
        'updated_by' => 1  // Assuming this is the user ID for 'updated_by'
    ]);
    }
}