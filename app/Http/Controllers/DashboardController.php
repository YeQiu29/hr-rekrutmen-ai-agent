<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Import User model

class DashboardController extends Controller
{
    public function hrdDashboard()
    {
        // Fetch all users with 'pelamar' role and their applicant profiles
        $applicants = User::where('role', 'pelamar')->with('applicantProfile')->get();

        return view('hrd.dashboard', compact('applicants'));
    }

    public function pelamarDashboard()
    {
        // This will now redirect to the profile page
        return redirect()->route('pelamar.profile');
    }
}
