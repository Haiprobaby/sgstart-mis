<?php

use Illuminate\Database\Seeder;
use App\SmSection;

class sm_sectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // SmSection::query()->truncate();

        DB::table('sm_sections')->insert([

            [
                'section_name' => 'A',
                'created_at' => date('Y-m-d h:i:s')
            ],

            [
                'section_name' => 'B',
                'created_at' => date('Y-m-d h:i:s')
            ],

            [
                'section_name' => 'C',
                'created_at' => date('Y-m-d h:i:s')
            ],

            [
                'section_name' => 'D',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'section_name' => 'E',
                'created_at' => date('Y-m-d h:i:s')
            ],


        ]);
    }
}
