<?php

use Illuminate\Database\Seeder;

class CpComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('r_cp_component_codes')->insert([
            [
                'component_code' => 100,
                'component_abbr' => 'CP',
                'comp_name' => 'MIRASOL TREATED PATHOGEN REDUCED COVID-19 CONVALESCENT PLASMA',
                'exp_interval_type' => 'Y',
                'exp_interval' => 1,
                'min_vol' => 150.00,
                'max_vol' => 250.00,
                'min_storage' => -18,
                'max_storage' => -89,
                'is_special' => 0,
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
            ],
            [
                'component_code' => 101,
                'component_abbr' => 'PRBC',
                'comp_name' => 'PACKED RED BLOOD CELL',
                'exp_interval_type' => 'D',
                'exp_interval' => 35,
                'min_vol' => 230.00,
                'max_vol' => 330.00,
                'min_storage' => 2,
                'max_storage' => 6,
                'is_special' => 0,
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
            ],
            [
                'component_code' => 102,
                'component_abbr' => 'PC',
                'comp_name' => 'PLATELET CONCENTRATE',
                'exp_interval_type' => 'D',
                'exp_interval' => 5,
                'min_vol' => 50.00,
                'max_vol' => 70.00,
                'min_storage' => 20,
                'max_storage' => 24,
                'is_special' => 0,
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
            ],
            [
                'component_code' => 103,
                'component_abbr' => 'LRPRBC',
                'comp_name' => 'LEUKOCYTE REDUCED PACKED RED BLOOD CELL',
                'exp_interval_type' => 'D',
                'exp_interval' => 42,
                'min_vol' => 230.00,
                'max_vol' => 330.00,
                'min_storage' => 2,
                'max_storage' => 6,
                'is_special' => 0,
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
            ],
            [
                'component_code' => 104,
                'component_abbr' => 'LRPC',
                'comp_name' => 'LEUKOCYTE REDUCED PLATELET CONCENTRATE',
                'exp_interval_type' => 'D',
                'exp_interval' => 5,
                'min_vol' => 50.00,
                'max_vol' => 70.00,
                'min_storage' => 20,
                'max_storage' => 24,
                'is_special' => 0,
                'disable_flg' => 'N',
                'created_by' => 'admin',
                'created_dt' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'updated_dt' => null,
            ],
        ]);
    }
}
