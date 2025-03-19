<?php

namespace Database\Seeders;

use App\Models\SelectOption;
use Illuminate\Database\Seeder;

class SelectOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $selectOptionData = [
            [
                'group' => 'test',
                'key' => 'tst',
                'value' => 'test.translated',
            ]
        ];
        

        SelectOption::insert($selectOptionData);
    
    }
}
