<?php

use App\SmClass;
use Illuminate\Database\Seeder;

class sm_classesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SmClass::query()->truncate();

        DB::table('sm_classes')->insert([
            [
                'class_name' => 'One', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Two', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Three', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Four', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Five', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Six', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Seven', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Eight', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Nine', 'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'class_name' => 'Ten', 'created_at' => date('Y-m-d h:i:s')
            ],
        ]);
    }
}
