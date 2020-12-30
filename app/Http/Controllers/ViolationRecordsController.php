<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ViolationRecordsController extends Controller
{
    public function index(){
        return view('violation_records.index');
    }
}
