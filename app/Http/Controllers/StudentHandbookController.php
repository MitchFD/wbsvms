<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StudentHandbookController extends Controller
{
    public function index(){
        return view('student_handbook.index');
    }
}
