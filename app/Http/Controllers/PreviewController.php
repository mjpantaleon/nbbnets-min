<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\Component;
use App\Template;
use Milon\Barcode\Facades\DNS1DFacade;

use Storage;
use Session;
use Response;

class PreviewController extends Controller
{
    public function showPreview(Request $data){

        $split = explode("-", $data->get('data'));

        $facility_cd = Session::get('userInfo')['facility']['facility_cd'];


        $label = $this->prepareTemplate($facility_cd,$split[1],$split[0]);
        return view('layouts.label')->withContent($label);

    }

    private function prepareTemplate($facility_cd,$donation_id,$component_cd){
        
        $facility = Facility::whereFacilityCd($facility_cd)->firstOrFail();

        // $unit = Component::with('component_code','donation_min.mbd_min','additionaltest','aliqoute_donation')
        //             ->whereDonationId($donation_id)
        //             ->whereComponentCd($component_cd)
        //             ->whereNotIn('comp_stat',['EXP','DIS','ISS'])
        //             ->get()->first();

        $unit = Component::with('component_code','donation_min','additionaltest','aliqoute_donation')
            ->whereDonationId($donation_id)
            ->whereComponentCd($component_cd)
            ->whereNotIn('comp_stat',['EXP','DIS','ISS'])
            ->get()->first();

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
        $template = str_replace('{{BARCODE}}','<div style="font-size:14px;font-family:arial;background:#fff;width:98%;height:50px;text-align:center;vertical-align:middle;padding:2px;"><img src="barcode/'.$donation_id.'" width="95%" height="30" /><center><b>'.$donation_id.'</b></center></div>',$template);
        $template = str_replace('{{ABO}}',$bt[0],$template);
        $template = str_replace('{{RH}}',$rh,$template);
        $template = str_replace('{{COMPONENT}}',$unit->component_code->comp_name,$template);
        $template = str_replace('{{VOLUME}}',$unit->component_vol,$template);
        $template = str_replace('{{COLLECTION_DATE}}',$collection_dt,$template);
        $template = str_replace('{{EXPIRATION_DATE}}',date('M d, Y',strtotime($unit->expiration_dt)).' 23:59:00',$template);
        $template = str_replace('{{STORE}}','Store at '.$unit->component_code->min_storage.' to '.$unit->component_code->max_storage.' &deg;C',$template);

        if($unit->donation){
            if($unit->additionaltest){
                $template = str_replace('{{ANTIBODY}}','ANTIBODY SCREENING : NEGATIVE',$template);
                $template = str_replace('{{NAT}}','NUCLIEC ACID TESTING : NEGATIVE',$template);
                $template = str_replace('{{ZIKA}}','ZIKA : NEGATIVE',$template);
            }else{
                $template = str_replace('{{ANTIBODY}}','',$template);
                $template = str_replace('{{NAT}}','',$template);
                $template = str_replace('{{ZIKA}}','',$template);
            }
        }else{
            $template = str_replace('{{ANTIBODY}}','',$template);
            $template = str_replace('{{NAT}}','',$template);
            $template = str_replace('{{ZIKA}}','',$template);

        }

         return $template;
    }

    private function getTemplate($facility_cd){

        $template = Template::whereFacilityCd($facility_cd)->first();

        if($template){
            return $template->template;
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
