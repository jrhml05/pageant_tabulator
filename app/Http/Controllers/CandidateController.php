<?php

namespace App\Http\Controllers;

use App\Models\Mr_candidate;
use App\Models\Ms_candidate;

class CandidateController extends Controller
{
    public function index()
    {
        $data['title'] = 'Candidates';
        $data['mr'] = Mr_candidate::orderBy('id')->get();
        $data['ms'] = Ms_candidate::orderBy('id')->get();

        return view('admin.candidates.index', compact('data'));
    }
}
