<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViolationEntriesController extends Controller
{
    public function index(){
        return view('violation_entries.index');
    }
}
