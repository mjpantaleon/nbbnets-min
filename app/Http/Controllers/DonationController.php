<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Donation;

use DB;

class DonationController extends Controller
{
    public function index(Request $request){
        $data = $request->except('_token');

        \Log::info($data);

        $donation_dt = $data['donation_dt'];

        // SELECT * FROM donations 
        // LEFT JOIN donors ON donations.donor_sn = donor.seqno
        // WHERE created_dt = '$donation_dt'
        // ORDER by created_dt DESC
        $query = "  SELECT d.donation_type, d.mh_pe_stat, d.collection_method, d.collection_stat,  d.donation_id,  
                    dd.fname, dd.mname, dd.lname
                    FROM donations d
                    LEFT JOIN donors dd ON d.donor_sn = dd.seqno 
                    WHERE d.created_dt = '$donation_dt' 
                    ORDER by d.created_dt DESC ";
        $donations = DB::select($query);

        // $donations = Donation::with('donor')
        //             // ->where('seqno')
        //             ->where('created_dt', 'LIKE' ,'%'.$donation_dt.'%')
        //             ->orderBy('created_dt', 'desc')
        //             ->get();
        
        \Log::info($donations);
        return response()->json($donations);
        
    }

    public function create(Request $request){
        $data = $request->except('_token');

        $facility_user = '13006_mj';
        $facility_cd = 13001;
        
        // GENERATE A SEQNO BASED ON FACILITY CODE.YEAR.6DIGIT'
        $year_now = date('Y');
        $donation_count = Donation::count();
        $donation_count = $donation_count + 1;

        $seqno = $facility_cd.$year_now. sprintf("%06d", $donation_count); //130012020000001

        // initialize data
        $donation_id = $data['donation_id'];
        $donor_sn = $data['donor_sn'];
        $sched_id = 'Walk-in';
        $pre_registered = 'N';
        $donation_type = $data['donation_type'];
        $collection_method = $data['collection_method'];
        $mh_pe_stat = $data['mh_pe_stat'];
        $collection_stat = $data['collection_stat'];

        $created_by = $facility_user;
        $created_dt = $data['created_dt'];
        $updated_by = $data['updated_by'];
        $updated_dt = date('Y-m-d H:i:s');

        // SAVE RECORD
        $donation = new Donation;
        $donation->seqno = $seqno;
        $donation->donation_id = $donation_id;
        $donation->donor_sn = $donor_sn;
        $donation->sched_id = $sched_id;
        $donation->pre_registered = $pre_registered;
        $donation->donation_type = $donation_type;
        $donation->collection_method = $collection_method;
        $donation->facility_cd = $facility_cd;
        $donation->mh_pe_stat = $mh_pe_stat;            // A
        $donation->collection_stat = $collection_stat;

        $donation->created_by = $created_by;
        $donation->created_dt = $created_dt;
        $donation->updated_by = $updated_by;
        $donation->updated_dt = $updated_dt;
        $donation->save();

        return "OK";
        \Log::info($id);
    }
}
