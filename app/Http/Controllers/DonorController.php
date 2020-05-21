<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donor;

use Illuminate\Support\Facades\DB;

class DonorController extends Controller
{
    public function index(){
        $donors = Donor::orderBy('created_dt', 'desc')->get();
        return $donors;  
    }

    public function donorDetails($id){
        $donor = Donor::where('seqno', $id)->first();
        return $donor;
    }

    public function getAge($id){
        $bdate = Donor::select('bdate')->where('seqno', $id)->first();
        
        $age = \Carbon::parse($bdate)->age;
        return $age;
    }

    public function search(Request $request){
        $data = $request->except('_token');
        
        $fname = $data['fname'];
        $mname = $data['mname'];
        $lname = $data['lname'];

        // If firstname is not empty OR middlename is nor empty OR lastname then
        if( ($fname != '') || ($mname != '') || ($lname != '') ){
            // check database where firstname is equals to data['fname]
            $query = Donor::query();
            if(!empty($fname)){
                $query = $query->where('fname', 'LIKE', '%'.$fname.'%');
            }

            if(!empty($mname)){
                $query = $query->where('mname', 'LIKE', '%'.$mname.'%');
            }

            // check database where lname is equals to data['lname]
            if(!empty($lname)){
                $query = $query->where('lname', 'LIKE', '%'.$lname.'%');
            }
            // order by created_dt in descending order
            $query = $query->orderBY('created_dt', 'desc');
            // get the first record found
            $keyword = $query->get();
            // return a response
            return response()->json($keyword);
            \Log::info($keyword);
        }
        // IF ALL FIELDS ARE EMPTY THEN
        // SHOULD HAVE A RESPONSE IF EMPTY
        
        
        // SELECT FROM donors WHERE fname, mname, lname like searched
        // $query = "  SELECT * FROM donors 
        //             WHERE 'fname' LIKE '%$fname%' 
        //             OR 'mname' LIKE '%$mname%' 
        //             OR 'lname' LIKE '%$lname%' ";
        // $keyword = DB::select($query);
        // return response()->json($keyword);
        // \Log::info($keyword);
        
        // if( ($fname != '') || ($mname != '') || ($lname != '')){
        //     $keyword = Donor::where(function ($q) use ($fname, $mname, $lname) {
        //             $q->orwhere('fname', 'LIKE', '%' .$fname. '%');
        //             $q->orWhere('mname', 'LIKE', '%' .$mname. '%');
        //             $q->orWhere('lname', 'LIKE', '%' .$lname. '%');
        //     })->get();
        //     return response()->json($keyword);
            
        //     \Log::info($keyword);
        // }
        // ->orderBy('created_dt', 'desc');
        
        // $keyword = Donor::orderBy('created_dt', 'desc')
        //     ->orWhere('fname', 'LIKE', '%' .$fname. '%')
        //     ->orWhere('mname', 'LIKE', '%' .$mname. '%') 
        //     ->orWhere('lname', 'LIKE', '%' .$lname. '%')
        //     ->get();
        // return response()->json($keyword);
        // \Log::info($keyword);

    }
}
