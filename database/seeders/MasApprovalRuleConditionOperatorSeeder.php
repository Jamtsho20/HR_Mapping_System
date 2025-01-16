<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasApprovalRuleConditionOperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operators = [
            ['name' => 'Is', 'value' => '='],
            ['name' => 'Is Not', 'value' => '!='],
            ['name' => 'Is Greater than', 'value' => '>'],
            ['name' => 'Is Less than', 'value' => '<'],
            ['name' => 'Is Less than or Equal to', 'value' => '<='],
            ['name' => 'Is Greater than or Equal to', 'value' => '>='],
        ];

        foreach ($operators as &$operator) {
            $operator['created_at'] = now();
            $operator['updated_at'] = now();
        }

        DB::table('mas_approval_rule_condition_operators')->insert($operators);
    }
}
