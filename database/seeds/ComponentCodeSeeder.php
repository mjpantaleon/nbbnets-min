<?php

use Illuminate\Database\Seeder;

class ComponentCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('r_component')->insert([
            [
                'component_cd' => '80',
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
                'comp_name' => 'PLASMA',
                'exp_interval' => 30,
                'exp_interval_type' => 'Y',
                'min_vol' => 250,
                'max_vol' => 0,
                'min_storage' => 0,
                'max_storage' => 0,
                'is_special' => 0,
            ],
            [
                'component_cd' => '81',
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
                'comp_name' => 'PLATELETS',
                'exp_interval' => 30,
                'exp_interval_type' => 'D',
                'min_vol' => 0,
                'max_vol' => 0,
                'min_storage' => 0,
                'max_storage' => 0,
                'is_special' => 0,
            ],
            [
                'component_cd' => '82',
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
                'comp_name' => 'RED CELL',
                'exp_interval' => 30,
                'exp_interval_type' => 'D',
                'min_vol' => 0,
                'max_vol' => 0,
                'min_storage' => 0,
                'max_storage' => 0,
                'is_special' => 0,
            ],
            [
                'component_cd' => '83',
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
                'comp_name' => 'WHITE BLOOD CELL',
                'exp_interval' => 30,
                'exp_interval_type' => 'D',
                'min_vol' => 0,
                'max_vol' => 0,
                'min_storage' => 0,
                'max_storage' => 0,
                'is_special' => 0,
            ],
            [
                'component_cd' => '84',
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
                'comp_name' => 'STEM CELL',
                'exp_interval' => 30,
                'exp_interval_type' => 'D',
                'min_vol' => 0,
                'max_vol' => 0,
                'min_storage' => 0,
                'max_storage' => 0,
                'is_special' => 0,
            ],
        ]);
    }
}
