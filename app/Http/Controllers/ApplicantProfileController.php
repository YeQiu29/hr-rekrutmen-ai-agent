<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ApplicantProfile;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\Storage;

class ApplicantProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->applicantProfile; // Attempt to load existing profile
        $vacancies = JobVacancy::where('status', 'open')->get();

        return view('pelamar.profile', compact('user', 'profile', 'vacancies'));
    }

    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'position_applied' => 'nullable|string|max:255',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Max 2MB
        ]);

        // Update user's name
        $user->name = $validatedData['name'];
        $user->save();

        // Handle CV upload
        $cvPath = $user->applicantProfile->cv_path ?? null;
        if ($request->hasFile('cv_file')) {
            // Delete old CV if exists
            if ($cvPath && Storage::disk('public')->exists($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }
            $cvPath = $request->file('cv_file')->store('cvs', 'public');
        }

        // Create or update applicant profile
        ApplicantProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                'education' => $validatedData['education'],
                'experience' => $validatedData['experience'],
                'position_applied' => $validatedData['position_applied'],
                'cv_path' => $cvPath,
            ]
        );

        return redirect()->route('pelamar.profile')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}