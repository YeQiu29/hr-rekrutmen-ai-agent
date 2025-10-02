<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobVacancy;

class CvAgentController extends Controller
{
    /**
     * Display the CV Agent page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (Auth::user()->role !== 'hrd') {
            abort(403, 'Hanya HRD yang dapat mengakses halaman ini.');
        }

        $vacancies = JobVacancy::all();

        return view('cv-agent.index', compact('vacancies'));
    }
}
