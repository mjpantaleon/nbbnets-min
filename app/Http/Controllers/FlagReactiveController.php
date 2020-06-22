<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Donation;
use App\Donor;

class FlagReactiveController extends Controller
{
    static function flag($donation_id,$facility_cd){
        $donation = Donation::with('donor','units','test','additionaltest')->whereDonationId($donation_id)->first();
        if(!$donation->test){
            if($donation->additionaltest){
                $at = $donation->additionaltest;
                if($at->nat == 'R' || $at->antibody == 'R' || $at->zika == 'R'){
                    self::doReactive($donation);
                }
            }
            return;
        }

        if($donation->test->result == 'R'){
            self::doReactive($donation);

        }else if($donation->test->result == 'N'){

            if($donation->additionaltest){
                $at = $donation->additionaltest;
                if($at->nat == 'R' || $at->antibody == 'R' || $at->zika == 'R'){
                    self::doReactive($donation);
                }
            }else if($donation->donor){
                $donation->donor->donor_id = Donor::generateNo($facility_cd);
                $donation->donor->donor_stat = 'A';
                $donation->donor->donation_stat = 'Y';
                $donation->donor->save();
            }
        }

        
    }

    static function flagReactive($donation_id){
        $donation = Donation::with('donor','units','test')->whereDonationId($donation_id)->first();
        $donation->donation_stat = 'REA';
        $donation->mh_pe_stat = 'PD';
        $donation->mh_pe_deferral = 'TTI';
        $donation->save();

        if($donation->donor){
            $donation->donor->donor_stat = 'PD';
            $donation->donor->donation_stat = 'N';
            $donation->donor->deferral_basis = '2.5.3';
            $donation->donor->save();
        }

        if($donation->units){
            foreach($donation->units as $i => $unit){
                $donation->units[$i]->comp_stat = 'QUA';
                $donation->units[$i]->save();
            }
        }
    }

    static function flagNonReactive($donation_id,$facility_cd){
        $donation = Donation::with('donor')->whereDonationId($donation_id)->first();
        if($donation->donor){
            if($donation->donor->donation_stat != 'Y'){
                $donation->donor->donor_id = Donor::generateNo($facility_cd);
                $donation->donor->donor_stat = 'A';
                $donation->donor->donation_stat = 'Y';
                $donation->donor->save();
            }
        }
    }

    static function doReactive($donation){
        $donation->donation_stat = 'REA';
        $donation->mh_pe_stat = 'PD';
        $donation->mh_pe_deferral = 'TTI';
        $donation->save();

        if($donation->donor){
            $donation->donor->donor_stat = 'PD';
            $donation->donor->donation_stat = 'N';
            $donation->donor->deferral_basis = '2.5.3';
            $donation->donor->save();
        }

        if($donation->units){
            foreach($donation->units as $i => $unit){
                $donation->units[$i]->comp_stat = 'QUA';
                $donation->units[$i]->save();
            }
        }
    }

}
