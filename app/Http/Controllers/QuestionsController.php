<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Questions;
use DB;

class QuestionsController extends Controller
{
    public function getMH(){
        $query = "  SELECT DISTINCT(qq.header_text), q.*
                    FROM questions q
                    LEFT JOIN questions_header qq ON q.header_id = qq.id
                    WHERE q.category = 'MH' AND q.disable_flag = 0";
        $questions = DB::select($query);
                    
        \Log::info($questions);
        return response()->json($questions); 
    }

    public function getPE(){
        $query = "  SELECT DISTINCT(qq.header_text), q.*
                    FROM questions q
                    LEFT JOIN questions_header qq ON q.header_id = qq.id
                    WHERE q.category = 'PE' AND q.disable_flag = 0";
        $questions = DB::select($query);
                    
        \Log::info($questions);
        return response()->json($questions); 
    }

}
