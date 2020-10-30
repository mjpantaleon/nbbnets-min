<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BloodRequest;
use Session;
use DB;

use App\BauPatient;
use App\BauBloodRequest;
use App\BauBloodRequestDetail;

// use App\RCpComponentCode;

class BloodRequestController extends Controller
{
    public function bloodRequestList(Request $request){
        $selected_dt    = date($request['selected_dt']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        /*
        SELECT request_id, reference, request_type, status 
        FROM `bau_blood_request` 
        WHERE created_dt BETWEEN '2018-01-01' AND '2018-10-31'
        AND facility_cd = '12002'


        SELECT br.request_id, br.reference, br.request_type, br.status, 
        bp.patient_id, bp.firstname, bp.lastname, bp.blood_type 
        FROM `bau_blood_request` br 
        LEFT JOIN `bau_patient` bp ON bp.patient_id = br.patient_id 
        WHERE br.created_dt LIKE '2020-10-31' 
        AND facility_cd = '13109
        */

        $sql =" SELECT br.request_id, br.reference, br.request_type, br.status, bp.patient_id, bp.firstname, bp.lastname, bp.blood_type 
                FROM `bau_blood_request` br
                LEFT JOIN `bau_patient` bp ON bp.patient_id = br.patient_id
                WHERE br.created_dt LIKE '%$selected_dt%'
                AND facility_cd = '$facility_cd'
                AND br.status = 'FLU' ";

        $result = DB::select($sql);
        \Log::info($result);
        
        $result = json_decode(json_encode($result), true);
        \Log::info($result);
        return $result;
    }

    public function forLookUp($id){
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        /*
        SELECT br.request_id, br.reference, br.request_type, br.status, bp.patient_id, bp.firstname, bp.middlename, bp.lastname, bp.name_suffix, bp.blood_type 
        FROM `bau_blood_request` br 
        LEFT JOIN `bau_patient` bp ON bp.patient_id = br.patient_id 
        WHERE br.request_id = '50076' 
        AND facility_cd = '13109'
        AND br.status = 'FLU'
        */

        $blood_request_detail = BauBloodRequest::select('request_id','reference','request_type','status')
                                            ->with('patient_min')
                                            ->whereRequestId($id)
                                            ->whereFacilityCd($facility_cd)
                                            ->where('status', '=', 'FLU')
                                            ->first()
                                            ->toArray();
        \Log::info($blood_request_detail);
        return $blood_request_detail;
                                                
        // $sql =" SELECT br.request_id, br.reference, br.request_type, br.status, bp.patient_id, bp.firstname, bp.middlename, bp.lastname, bp.name_suffix, bp.blood_type 
        //         FROM `bau_blood_request` br
        //         LEFT JOIN `bau_patient` bp ON bp.patient_id = br.patient_id
        //         WHERE br.request_id = '$id'
        //         AND facility_cd = '$facility_cd'
        //         AND br.status = 'FLU' ";

        // $blood_request_detail = DB::select($sql);
        // \Log::info($blood_request_detail);
        
        // $blood_request_detail = json_decode(json_encode($blood_request_detail), true);
        // \Log::info($blood_request_detail);
        // return $blood_request_detail;
    }

    public function getCpComponents(){
        $ids = [];
        $sql = "    SELECT component_code, component_abbr, comp_name
                    FROM `r_cp_component_codes`
                    WHERE disable_flg = 'N' ";
        $cp_comp = DB::select($sql);
        \Log::info($cp_comp);
        
        $cp_comp = json_decode(json_encode($cp_comp), true);
        \Log::info($cp_comp);
        // return $cp_comp;
        
        if($cp_comp){
            for($i = 0; $i < count($cp_comp); $i++){
                $ids[$i]['component_code'] = $cp_comp[$i]['component_code'];
                $ids[$i]['component_abbr'] = $cp_comp[$i]['component_abbr'];
                $ids[$i]['comp_name'] = $cp_comp[$i]['comp_name'];
                $ids[$i]['requested_units'] = "";
            }
            \Log::info($ids);
            return $ids;
        }
    }

    // public function getAgencies(Request $request){
    //     $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

    //     $sql = "    SELECT agency_cd, agency_name
    //                 FROM `r_donor_agency` 
    //                 WHERE facility_cd = '$facility_cd'
    //                 AND agency_cd LIKE '%$facility_cd%' ";

    //     $agencies = DB::select($sql);
    //     \Log::info($agencies);
        
    //     $agencies = json_decode(json_encode($agencies), true);
    //     \Log::info($agencies);

    //     // return $agencies;
    // }


    public function create(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $verifier       = $request->get('verifier');

        \Log::info($facility_user);
        
        // bau_patient

        $bau_patient_status = BauPatient::create(
            array(
                'firstname'     => $request['fname'],
                'middlename'    => $request['mname'],
                'lastname'      => $request['lname'],
                'name_suffix'   => $request['name_suffix'],
                'blood_type'    => $request['blood_type'],
                'gender'        => $request['gender'],
                'civil_status'  => $request['civil_stat'],
                'birthday'      => date('Y-m-d', strtotime($request['bdate'])),
                'age'           => $request['age'],
                'address'       => $request['address'],
                'diagnosis'     => $request['diagnosis'],
                'created_by'    => $facility_user,
                'created_dt'    => date('Y-m-d H:i:s'),
            )
        );

        if($bau_patient_status){

            $year_now = date('Y');              // 2020
            $request_count = BauBloodRequest::count(); 
            $request_count = $request_count + 1;
            $reference = 'CP-'.$year_now . sprintf("%07d", $request_count); // 1300620200000004


            $bau_request_status = BauBloodRequest::create(
                array(
                    'request_type'  => 'CP',
                    'facility_cd'   => $facility_cd,
                    'agency_cd'     => $facility_cd,
                    'patient_id'    => $bau_patient_status->patient_id,
                    'status'        => 'FLU',    /* FLU = FOR LOOK UP, FR = FOR RESERVATION */
                    'reference'     => '-',
                    'created_by'    => $facility_user,
                    'created_dt'    => date('Y-m-d H:i:s'),
                    'updated_by'    => '-',                     /* this should accept null value */
                    'updated_dt'     => date('Y-m-d H:i:s'),    /* this should accept null value */
                )
            );

            if($bau_request_status->request_id){

                $save_array = [];

                foreach ($request['requested_units'] as $key => $value) {
                    
                    for ($i=0; $i < $value['requested_units']; $i++) { 
                        $save_array[] = array(
                            'request_id'        => $bau_request_status->request_id,
                            'component_cd'      => $value['component_code'],
                            'blood_type'        => $request['blood_type'],
                            'donation_id'       => NULL,
                            'component_status'  => 'FR', 
                            'created_by'        => "$facility_cd",
                            'created_dt'        => date('Y-m-d H:i:s'),
                        );
                    }
                }

                \Log::info($save_array);

                $status = BauBloodRequestDetail::insert($save_array);

                \Log::info($status);

                if($status){
                    // return goodshit
                    return response()->json([
                        'message' => "Blood Request sent",
                        'status' => 1
                    ], 200);
                } else{
                    // return badshit
                    return response()->json([
                        'message' => "OPPSS! Error saving Blood Request",
                        'status' => 1
                    ], 200);
                }

            } else{
                //error in saving bau_blood_request
            }


        } else{
            //error in saving bau_patient
        }

    }


}
