<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call(SystemMenusTableSeeder::class);
        $this->call(SystemSubMenusTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UserRolesTableSeeder::class);
        $this->call(RolePermissionsTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        $this->call(MasSectionSeeder::class);
        $this->call(MasDesignationSeeder::class);
        $this->call(MasDzongkhagSeeder::class);
        $this->call(MasGewogSeeder::class);
        $this->call(MasVillagesSeeder::class);
        $this->call(MasRegionsSeeder::class);
        $this->call(MasRegionLocationsSeeder::class);
        $this->call(MasOfficesSeeder::class);
        $this->call(MasGradeSeeder::class);
        $this->call(MasGradeStepsSeeder::class);
        $this->call(MasAccountHeadsSeeder::class);
        $this->call(MasPaySlabSeeder::class);
        $this->call(MasPayHeadsSeeder::class);
        $this->call(MasPayGroupSeeder::class);
        $this->call(MasPaySlabDetailSeeder::class);
        $this->call(ApprovingAuthoritySeeder::class);
      
    }
}