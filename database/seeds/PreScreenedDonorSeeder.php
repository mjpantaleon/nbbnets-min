<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Donor;

class PreScreenedDonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $facility_cd = '11001';
        $year_now = date('Y');

        foreach (range(1,10) as $index) {

            $donors_count = Donor::count(); 
            $donors_count = $donors_count + $index;
    
            $seqno = $facility_cd . $year_now . sprintf("%07d", $donors_count);

            DB::table('pre_screened_donors')->insert([
                'donor_sn'          => $seqno,
                'last_name'         => $faker->lastName,
                'first_name'        => $faker->firstName,
                'middle_name'       => $faker->lastName,
                'name_suffix'       => '',
                'nationality'       => 'FILIPINO',
                'gender'            => $faker->randomElement(['M', 'F']),
                'bdate'             => '1980-01-01',
                'age'               => '40',
                'weight'            => $faker->numberBetween($min = 50, $max = 180),
                'address'           => $faker->address,
                'email'             => $faker->email,
                'fb'                => '',
                'mobile_no'         => '09' . $faker->numerify('#########'),
                'time_availability' => $faker->time($format = 'H:i:s', $max = 'now'), // '20:49:42',
                'first_answer'      => 1,
                'second_answer'     => '',
                'not_sure_answer'   => "['a', 'b', 'c', 'd', 'e']",
                'facility_cd'       => $facility_cd,
                'created_by'        => $facility_cd . '_1',
                'created_dt'        => date('Y-m-d H:i:s'),
                'status'            => 0,
                'approved_by'       => null,
                'approval_dt'       => null,
            ]);
        }
        
    }
}
