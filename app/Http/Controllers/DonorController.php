<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donor;

use Carbon\Carbon;

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
        
        $age = Carbon::parse($bdate)->age;
        return $age;
    }

    // public function search(Request $request){
    //     $data = $request->except('_token');

    //     //  SELECT FROM donors WHERE fname, mname, lname like searched
    //     $keyword = Donor::orderBy('created_dt', 'desc')
    //     ->where('fname', 'LIKE', '%' .$data. '%')
    //     ->orWhere('mname', 'LIKE', '%' .$data. '%') 
    //     ->orWherHas('lname', 'LIKE', '%' .$data. '%')
    //     ->get();
        
    //     return response()->json($keyword);
    // }
}
