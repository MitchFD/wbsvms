<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ViolationEntryController extends Controller
{
    public function index(){
        return view('violation_entry.index');
    }
}
