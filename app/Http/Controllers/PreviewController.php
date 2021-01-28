<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\Component;
use App\Donation;
use App\Template;
use App\AdditionalTest;
use Milon\Barcode\Facades\DNS1DFacade;

use Storage;
use Session;
use Response;

class PreviewController extends Controller
{
    public function showPreview(Request $data){

        $method = explode(",", $data->get('data'));

        if($method[1] == 'CP'){

            $facility_cd = Session::get('userInfo')['facility']['facility_cd'];
    
            $label = $this->prepareTemplate($facility_cd,$method[0],100,$method[1]);
            return view('layouts.label')->withContent($label);

        } else{

            $split = explode("-", $method[0]);
            $facility_cd = Session::get('userInfo')['facility']['facility_cd'];

            if(count($split) == 3){
                $label = $this->prepareTemplate($facility_cd,$split[1] . "-" . $split[2],$split[0],$method[1]);
            } else{
                $label = $this->prepareTemplate($facility_cd,$split[1],$split[0],$method[1]);
            }
            
            return view('layouts.label')->withContent($label);

        }

    }

    private function prepareTemplate($facility_cd, $donation_id, $component_cd, $method){
        
        $facility = Facility::whereFacilityCd($facility_cd)->firstOrFail();

        // $unit = Component::with('component_code','donation_min.mbd_min','additionaltest','aliqoute_donation')
        //             ->whereDonationId($donation_id)
        //             ->whereComponentCd($component_cd)
        //             ->whereNotIn('comp_stat',['EXP','DIS','ISS'])
        //             ->get()->first();

        $unit = Component::with('cp_component_code','donation_min','aliqoute_donation')
            ->whereDonationId($donation_id)
            ->whereComponentCd($component_cd)
            ->whereNotIn('comp_stat',['EXP','DIS','ISS'])
            ->get()->first();

        if($method == 'WB' && strpos($donation_id, '-')){

            $donation_min = Donation::select('donation_id','sched_id','blood_bag','created_dt', 'collection_method', 'collection_type')
                            ->where('donation_id', explode('-', $donation_id)[0])
                            ->first();

            $unit->donation_min = $donation_min;

        }

        if(!$unit){
            return '<div style="text-align:center;font-family:calibri;">Opps! Sorry, Blood Unit no longer available!</div>';
        }

        if($unit->blood_type != null || $unit->blood_type != ''){
            $bt = explode(' ',$unit->blood_type);
            $rh = strtoupper($bt[1]) == 'POS' ? 'POSITIVE' : 'NEGATIVE';
        }else{
            $blood_typing = TypingResult::whereDonationId($donation_id)->first();
            if(!$blood_typing){
                dd("Something wen't wrong, please contact NVBSP IMU");
            }

            $bt = explode(' ',$blood_typing->blood_type);
        }

        if($unit->source_donation_id){
            $collection_dt = $unit->aliqoute_donation->created_dt;
            $collection_dt = date('M d, Y',strtotime($collection_dt));
        }else{
            $collection_dt = $unit->donation_min->created_dt;
            $collection_dt = date('M d, Y',strtotime($collection_dt));
        }

        $template = $this->getTemplate($facility_cd);
        
        $template = str_replace('{{FACILITY_NAME}}',$facility->facility_name,$template);
        $template = str_replace('{{BARCODE}}','<div style="font-size:14px;font-family:arial;background:#fff;width:98%;height:47.5px;text-align:center;vertical-align:middle;padding:2px;"><img src="barcode/'.$donation_id.'" width="95%" height="30" /><center><b>'.$donation_id.'</b></center></div>',$template);
        $template = str_replace('{{ABO}}',$bt[0],$template);
        $template = str_replace('{{RH}}',$rh,$template);
        $template = str_replace('{{COMPONENT}}',$unit->cp_component_code->comp_name,$template);
        $template = str_replace('{{VOLUME}}',$unit->component_vol,$template);

        // Add Riboflavin
        if($component_cd == 100){
            // $template = str_replace('{{ADDRB}}', "+ 35 ml Riboflavin", $template); *old value
            $template = str_replace('{{ADDRB}}', "",$template);
        } else{
            $template = str_replace('{{ADDRB}}', "", $template);
        }

        // Anticoagulant

        $anticoagulant = "";

        if($method == 'CP'){
            $anticoagulant = "Anticoagulant: ACD-A";
        } else{

            if($unit->donation_min->blood_bag == 'Q'){
                // $anticoagulant = "CPD Anticoagulant"; *old value
                $anticoagulant = "Anticoagulant: CPD";
            } else{
                // $anticoagulant = "CPDA-1 Anticoagulant"; *old value
                $anticoagulant = "Anticoagulant: CPD";
            }

            if($component_cd == 101 || $component_cd == 103){
                $anticoagulant = $anticoagulant . " w/ SAG-M";
            }

        }

        $template = str_replace('{{ANTICOAGULANT}}', $anticoagulant, $template);
        
        $template = str_replace('{{COLLECTION_DATE}}',$collection_dt,$template);
        $template = str_replace('{{EXPIRATION_DATE}}',date('M d, Y',strtotime($unit->expiration_dt)).' 23:59:00',$template);
        $template = str_replace('{{STORE}}','Store and transport at '.$unit->cp_component_code->min_storage.' to '.$unit->cp_component_code->max_storage.' &deg;C',$template);

        $main_donation = $donation_id;

        if(strpos($donation_id, '-') !== 0){
            $main_donation = explode('-',$donation_id)[0];
        }


        $add_test_result = AdditionalTest::select('antibody', 'nat', 'zika')
                                            ->where('donation_id', $main_donation)
                                            ->first();


        if($add_test_result){

            if($add_test_result['nat'] == 'N'){
                // * old value: line 164 for zika negative result, changed ! @Jan 28, 2021: CHANGED comp_name from 'COVID-19 CONVALESCENT PLASMA' to 'CONVALESCENT PLASMA' where component_code = '100'
                $template = str_replace('{{NAT}}','ID NAT (HBV, HCV, HIV),',$template);
            } else{
                $template = str_replace('{{NAT}}', '' ,$template);
            }
            
            if($add_test_result['zika']  == 'N'){
                // * remove (HBV, HCV, HIV) then add 'ZIKA: NEGATIVE'
                $template = str_replace('{{ZIKA}}','ZIKA: NEGATIVE,',$template);
            } else{
                $template = str_replace('{{ZIKA}}','',$template);
            }

            if($add_test_result['antibody'] == 'N'){
                $template = str_replace('{{ANTIBODY}}','ANTIBODY SCREENING : NEGATIVE',$template);
            } else{
                $template = str_replace('{{ANTIBODY}}','',$template);
            }

        }

        // if($unit->donation){
        //     if($unit->additionaltest){
        //         $template = str_replace('{{ANTIBODY}}','ANTIBODY SCREENING : NEGATIVE',$template);
        //         $template = str_replace('{{NAT}}','NUCLIEC ACID TESTING : NEGATIVE',$template);
        //         $template = str_replace('{{ZIKA}}','ZIKA : NEGATIVE',$template);
        //     }else{
        //         $template = str_replace('{{ANTIBODY}}','',$template);
        //         $template = str_replace('{{NAT}}','',$template);
        //         $template = str_replace('{{ZIKA}}','',$template);
        //     }
        // }else{
        //     $template = str_replace('{{ANTIBODY}}','',$template);
        //     $template = str_replace('{{NAT}}','',$template);
        //     $template = str_replace('{{ZIKA}}','',$template);

        // }

         return $template;
    }

    private function getTemplate($facility_cd){

        $template = Template::whereFacilityCd($facility_cd)->first();

        if($template){
            // return $template->template;
        }

        $file = Storage::get('label-template.html');
        return $file;

    }

    public function barcode($donation_id){
        $barcode = DNS1DFacade::getBarcodePNG($donation_id, "C128",2,30);
        $barcode = base64_decode($barcode);
        $response = Response::make($barcode);
        $response->header('Content-Type','image/png');
        return $response;
    }

}
