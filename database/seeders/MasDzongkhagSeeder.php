<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasDzongkhagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('
        INSERT INTO `mas_dzongkhags` (`dzongkhag`, `created_by`) VALUES
        ("Bumthang", 1),
        ("Chukha", 1),
        ("Dagana", 1),
        ("Gasa", 1),
        ("Haa", 1),
        ("Lhuntse", 1),
        ("Mongar", 1),
        ("Paro", 1),
        ("Pemagatshel", 1),
        ("Punakha", 1),
        ("Samdrup Jongkhar", 1),
        ("Samtse", 1),
        ("Sarpang", 1),
        ("Thimphu", 1),
        ("Trashigang", 1),
        ("Trashiyangtse", 1),
        ("Trongsa", 1),
        ("Tsirang", 1),
        ("Wangdue Phodrang", 1),
        ("Zhemgang", 1);
    ');
    }
}
