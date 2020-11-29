<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BloodRequest;
use App\Component;
use Session;
use DB;

use App\BauPatient;
use App\BauBloodRequest;
use App\BauBloodRequestDetail;
use App\BauPhysician;

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


        SELECT br.request_id, br.reference, br.request_type, br.status, bp.patient_id, bp.firstname, bp.lastname, bp.blood_type, brd.component_cd, brd.donation_id
        FROM `bau_blood_request` br
        LEFT JOIN `bau_patient` bp ON bp.patient_id = br.patient_id
        LEFT JOIN `bau_blood_request_dtls` brd ON br.request_id = brd.request_id
        WHERE br.created_dt LIKE '%2020-11-02%'
        AND facility_cd = '13109'
        AND br.status = 'FLU'
        AND brd.donation_id IS NULL
        */

        $sql = BauBloodRequest::select('request_id', 'reference', 'request_type', 'status', 'patient_id')->distinct()
                            ->where('created_dt', 'LIKE', '%' .$selected_dt. '%')
                            ->whereFacilityCd($facility_cd)
                            ->with('details')
                            ->with('patient_details')
                            ->get()
                            ->toArray();
        \Log::info($sql);
        return $sql;

        // $sql =" SELECT DISTINCT(br.request_id), br.reference, br.request_type, br.status, bp.patient_id, bp.firstname, bp.middlename, bp.lastname, bp.blood_type 
        //         FROM `bau_blood_request` br
        //         LEFT JOIN `bau_patient` bp ON bp.patient_id = br.patient_id
        //         LEFT JOIN `bau_blood_request_dtls` brd ON br.request_id = brd.request_id
        //         WHERE br.created_dt LIKE '%$selected_dt%'
        //         AND facility_cd = '$facility_cd' ";

        // $result = DB::select($sql);
        // // \Log::info($result);
        
        // $result = json_decode(json_encode($result), true);
        // // \Log::info($result);
        // return $result;
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

        // ELOQUENT ! DO NOT FORGET TO SELECT 'patient_id' 
        $blood_request_detail = BauBloodRequest::select('request_id','patient_id','reference','request_type','status')
                                            ->with('patient_min')
                                            ->with('details')
                                            ->whereRequestId($id)
                                            ->whereFacilityCd($facility_cd)
                                            ->first()
                                            ->toArray();    
        return $blood_request_detail;
    }

    public function getCpComponents(){
        $ids = [];
        $sql = "    SELECT component_code, component_abbr, comp_name
                    FROM `r_cp_component_codes`
                    WHERE disable_flg = 'N' ";
        $cp_comp = DB::select($sql);
        // \Log::info($cp_comp);
        
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
            // \Log::info($ids);
            return $ids;
        }
    }
    
    public function getAvailableCpUnits(Request $request){
        $ids = [];
        $blood_type = $request->get('selected_blood_type');
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $date = date('Y-m-d');

        // \Log::info($blood_type);
        
        // $available_cp_units = Component::whereCompStat('AVA')
        //                             ->where('blood_type', '=', $blood_type)
        //                             ->get();

        /*
        SELECT c.donation_id, c.component_cd, c.blood_type, c.comp_stat , cp.comp_name
                    FROM `component` c
                    LEFT JOIN `r_cp_component_codes` cp ON c.component_cd = cp.component_code
                    WHERE `blood_type` = 'A pos'
                    AND `comp_stat` = 'AVA'  
                    AND `location` = '13109'
                    AND `component_cd` >= 100
                    ORDER BY c.created_dt ASC
        */

        $sql = "    SELECT c.donation_id, c.component_cd, c.blood_type, c.comp_stat , cp.comp_name, d.gender, dd.collection_method
                    FROM `component` c
                    LEFT JOIN `r_cp_component_codes` cp ON c.component_cd = cp.component_code
                    LEFT JOIN `donation` dd ON dd.donation_id = c.donation_id
                    LEFT JOIN `donor` d ON d.seqno = dd.donor_sn
                    WHERE `blood_type` = '$blood_type'
                    AND `comp_stat` = 'AVA'  
                    AND `location` = '$facility_cd'
                    AND `component_cd` >= 100
                    AND `expiration_dt` >= '$date'
                    AND (dd.collection_method = 'WB' OR ISNULL(dd.collection_method))
                    ORDER BY c.created_dt ASC ";
        
        $available_cp_units = DB::select($sql);

        // $available_cp_units = Component::where('blood_type', $blood_type)
        //                 ->select('donation_id', 'component_cd', 'blood_type', 'comp_stat')
        //                 ->with('donation')
        //                 ->get();


        $available_cp_units = json_decode(json_encode($available_cp_units), true);
        // \Log::info($available_cp_units);  


        // return $available_cp_units;

        if($available_cp_units){
            // for($i = 0; $i < count($available_cp_units); $i++){
            foreach($available_cp_units as $key => $val){
                $ids[$key]['donation_id'] = $val['donation_id'];
                $ids[$key]['component_cd'] = $val['component_cd'];
                $ids[$key]['blood_type'] = $val['blood_type'];
                $ids[$key]['comp_name'] = $val['comp_name'];
                $ids[$key]['comp_stat'] = $val['comp_stat'];
                $ids[$key]['gender'] = $val['gender'];
                $ids[$key]['selected_item'] = false;
            }

            // \Log::info($ids);
            return $ids;

        } else {
            return false;
        }
        // return $available_cp_units;
    }

    public function create(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $verifier       = $request->get('verifier');

        // \Log::info($facility_user);
        
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
                
                // INSERT PHYSICIAN RECORD
                $physician_record = BauPhysician::create(
                    array(
                        'request_id' => $bau_request_status->request_id,
                        'fname'         => $request['dr_fname'],
                        'mname'         => $request['dr_mname'],
                        'lname'         => $request['dr_lname'],
                        'name_suffix'   => $request['dr_name_suffix'],
    
                        'mobile_num'    => $request['mobile_no'],
                        'email'         => $request['email'],
                        'hospital'      => $request['hospital'],
                    )
                );

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

                // \Log::info($save_array);
                
                $status = BauBloodRequestDetail::insert($save_array);
                
                // \Log::info($status);
                
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
    

    public function reserveBloodUnits(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        
        $verifier       = $request->get('verifier');
        
        // \Log::info($request);
        
        $post_data              = $request->get('post_data');
        $component_details      = $request->get('component_details');
        
        // loop component_details
        foreach($component_details as $cd => $cdv){

            if(!$cdv['donation_id']){
                
                $request_id = $cdv['request_id'];
                $request_component_id = $cdv['request_component_id'];
                
                // CHECK IF REQUEST ID EXISTS AT bau_ blood_request_details TABLE
                $check_request_id = BauBloodRequestDetail::where('request_id', '=', $request_id)
                                                        ->first();
    
                if($check_request_id){
                    
                    foreach($post_data as $key => $value){
                        $donation_id = $value['donation_id'];
                        
                        // UPDATE THIS FUCKING SHIT
    
                        // match component_cd
                        if($cdv['component_cd'] == $value['component_cd']){
                            // BLOOD REQUEST DETAILS
                            BauBloodRequestDetail::where('request_component_id', $request_component_id)
                                                ->where('request_id', $request_id)
                                                ->update(['donation_id' => $donation_id]);
                            
                             // UPDATE COMPONENT
                             $update_component = Component::where('donation_id', $donation_id)
                             ->where('component_cd', $value['component_cd'])
                             ->update(['comp_stat' => 'RES']);
                             
                            // \Log::info($update_component);
                            
                            // remove value
                            unset($post_data[$key]);
                            
                            // exit from loop
                            break;
    
                           
                        } 
                    }   
                } else{
                    return response()->json([
                        'message' => "Donation ID not found!",
                        'status' => 1
                    ], 200);
                }
            }

        }

        $dtls_stat = self::getDetailsStatus($component_details[0]['request_id']);
        \Log::info($dtls_stat);


        return response()->json([
            'message' => "Component Details has been added successfully",
            'status' => 1
        ], 200);


    }

    private function getDetailsStatus($data){

        $details = BauBloodRequestDetail::where('request_id', '=', $data)
                            ->get();

        foreach($details as $key => $val){
            \Log::info($val['donation_id']);
            if($val['donation_id'] == null){
                return "false";
            }
        }

        // UPDATE status at bau_blood_request
        BauBloodRequest::where('request_id', $data)
                        ->update(['status' => 'RES']);
        //
        return "true";

    }


    // ISSUE BLOOD UNIT MODULE ////////////////////////////////////////////////////////////////
    public function getBloodRequestDetails($id){
        $request_details = BauBloodRequest::where('request_id', $id)
                        ->with('patient_details')
                        ->with('physician_details')
                        ->with('details')
                        ->first()
                        ->toArray();

        // $sql = "    SELECT * 
        //             FROM `bau_blood_request`
        //             WHERE `request_id` = '$id' ";
        // $request_details = DB::select($sql);

        \Log::info($request_details);       
        return $request_details;
    }

    public function issueBloodRequest($id){
        $year_now = date('Y');              // 2020
        $request_count = BauBloodRequest::count(); 
        $request_count = $request_count + 1;
        $reference = 'CP-'.$year_now . sprintf("%07d", $request_count); // CP-20200000004

        $issue_blood_request = BauBloodRequest::where('request_id', $id)
                    ->update(['status' => 'Released', 'reference' => $reference]);
        \Log::info($issue_blood_request);

        return response()->json([
            'message' => "Blood request has been issued successfully",
            'status' => 1
        ], 200);

    }
    
    // ISSUE BLOOD UNIT MODULE ///////////////////////////////////////////////////////////////
    public function cancelBloodRequest(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        
        $verifier       = $request->get('verifier');
        
        $blood_request_id   = $request->get('request_id'); 
        // \Log::info($cancel_blood_request);

        // FIRST CHECK BLOOD_REQUEST_DTLS IF THERE WERE RECORDS WITH DONATION ID UNDER THIS REQUEST_ID
        $check_for_reserved_units = BauBloodRequestDetail::where('request_id', $blood_request_id)
                            ->whereNotNull('donation_id')
                            ->get()
                            ->toArray();

        // \Log::info($check_for_reserved_units);
        // return $check_for_reserved_units;

        // if there were NO reserved units under this request_id then proceed to
        if(!$check_for_reserved_units){

              // UPDATE BAU BLOOD REQUEST TABLE
              BauBloodRequest::where('request_id', $blood_request_id)
              ->update(['status' => 'Cancelled']);
        }

        // else if there were RESERVED units under this request_id then proceed to
        else{

            // get each record then proceed to
            foreach($check_for_reserved_units as $key => $value){
                $donation_id = $value['donation_id'];
                $request_id = $value['request_id'];
    
                // UPDATE component SET comp_stat = 'AVA' WHERE donation_id = $donation_id
                Component::where('donation_id', $donation_id)
                        ->update(['comp_stat' => 'AVA']);
    
                // UPDATE BauBloodRequestDetail SET donation_id = null WHERE request_id = $blood_request_id
                BauBloodRequestDetail::where('request_id', $blood_request_id)
                                    ->update(['donation_id' => null]);
    
                // UPDATE BAU BLOOD REQUEST TABLE
                BauBloodRequest::where('request_id', $blood_request_id)
                                ->update(['status' => 'Cancelled']);
            }
        }

        // return response after each loop
        return response()->json([
            'message' => "Blood request has been cancelled successfully",
            'status' => 1
        ], 200);

    }

    public function getDataForIssuance($id){
        /* 
            SELECT br.request_id, bd.hospital, p.firstname, p.middlename, p.lastname, p.name_suffix, p.age, p.gender, p.blood_type, c.donation_id, c.collection_dt, c.expiration_dt
            FROM `bau_blood_request` br
            LEFT JOIN `bau_blood_request_dtls` brd ON brd.request_id = br.request_id
            LEFT JOIN `bau_patient` p ON p.patient_id = br.patient_id
            LEFT JOIN `bau_physicians` bd ON bd.request_id = br.request_id
            LEFT JOIN `component` c ON c.donation_id = brd.donation_id
            WHERE br.request_id = '50061'
            AND c.comp_stat = 'RES'
            AND p.blood_type = brd.blood_type
            AND br.reference IS NOT NULL

        */

        $data_for_issuance = BauBloodRequest::where('request_id', $id)
                        ->with('patient_details')
                        ->with('physician_details')
                        // ->with('details')
                        ->first()
                        ->toArray();
        // \Log::info($data_for_issuance);

        return $data_for_issuance;
    }

    public function issuedBloodUnits($id){
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $sql = "    SELECT DISTINCT(cp.component_abbr), c.donation_id, c.collection_dt, c.expiration_dt, c.comp_stat 
                    FROM `bau_blood_request_dtls` brd
                    LEFT JOIN `component` c ON c.donation_id = brd.donation_id
                    LEFT JOIN `r_cp_component_codes` cp ON cp.component_code = c.component_cd
                    WHERE brd.request_id = '$id' 
                    AND c.comp_stat = 'RES' 
                    AND c.location = '$facility_cd' 
                    ORDER BY c.created_dt ASC   ";
        $issued_units = DB::select($sql);

        $issued_units = json_decode(json_encode($issued_units), true);
        
        return $issued_units;

        // $issued_units = BauBloodRequestDetail::where('request_id', $id)
        //                 ->where('donation_id', '!=' , null)
        //                 ->with('reserved_units')
        //                 ->get()
        //                 ->toArray();
        // \Log::info($issued_units);
    }

}
