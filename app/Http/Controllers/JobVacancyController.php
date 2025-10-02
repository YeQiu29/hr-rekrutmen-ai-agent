<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'hrd') {
            abort(403, 'Hanya HRD yang dapat mengakses halaman ini.');
        }

        $vacancies = JobVacancy::latest()->get();

        return view('loker.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'hrd') {
            abort(403, 'Hanya HRD yang dapat mengakses halaman ini.');
        }

        return view('loker.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'hrd') {
            abort(403, 'Hanya HRD yang dapat mengakses halaman ini.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:job_vacancies,title',
            'description' => 'nullable|string',
        ]);

        // Generate filename from title
        $filename = Str::slug($validatedData['title']) . '.txt';
        $validatedData['filename'] = $filename;

        // Create vacancy in Laravel DB
        $vacancy = JobVacancy::create($validatedData);

        // Send request to Python API to create the .txt file
        try {
            $fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001');
            $response = Http::post("{$fastApiUrl}/job-description/", [
                'filename' => $filename,
                'content' => $validatedData['description'] ?? '',
            ]);

            if (!$response->successful()) {
                // If the API call fails, flash a warning but continue
                return redirect()->route('loker.index')->with('warning', 'Loker berhasil disimpan, tetapi gagal sinkronisasi file ke server AI: ' . $response->body());
            }

        } catch (\Exception $e) {
            // If the Python API is down, flash a warning.
            return redirect()->route('loker.index')->with('warning', 'Loker berhasil disimpan, tetapi server AI tidak dapat dihubungi.');
        }

        return redirect()->route('loker.index')->with('success', 'Lowongan kerja berhasil ditambahkan dan disinkronkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Auth::user()->role !== 'hrd') {
            abort(403, 'Hanya HRD yang dapat mengakses halaman ini.');
        }

        $vacancy = JobVacancy::findOrFail($id);

        return view('loker.edit', compact('vacancy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Auth::user()->role !== 'hrd') {
            abort(403, 'Hanya HRD yang dapat mengakses halaman ini.');
        }

        $vacancy = JobVacancy::findOrFail($id);

        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('job_vacancies')->ignore($vacancy->id)],
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['open', 'closed'])],
        ]);

        $fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001');
        $newFilename = $vacancy->filename;
        $titleChanged = $vacancy->title !== $validatedData['title'];

        // Jika judul berubah, nama file juga berubah
        if ($titleChanged) {
            $oldFilename = $vacancy->filename;
            $newFilename = Str::slug($validatedData['title']) . '.txt';
            $validatedData['filename'] = $newFilename;

            // Hapus file .txt lama di server Python
            try {
                Http::delete("{$fastApiUrl}/job-description/{$oldFilename}");
            } catch (\Exception $e) {
                // Abaikan jika gagal, mungkin file tidak ada atau server down
            }
        }

        // Perbarui data di database Laravel
        $vacancy->update($validatedData);

        // Buat/Perbarui file .txt baru di server Python
        try {
            $response = Http::post("{$fastApiUrl}/job-description/", [
                'filename' => $newFilename,
                'content' => $validatedData['description'] ?? '',
            ]);
            if (!$response->successful()) {
                return redirect()->route('loker.index')->with('warning', 'Loker berhasil diperbarui, tetapi gagal sinkronisasi file ke server AI: ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('loker.index')->with('warning', 'Loker berhasil diperbarui, tetapi server AI tidak dapat dihubungi.');
        }

        return redirect()->route('loker.index')->with('success', 'Lowongan kerja berhasil diperbarui dan disinkronkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->role !== 'hrd') {
            abort(403, 'Hanya HRD yang dapat mengakses halaman ini.');
        }

        $vacancy = JobVacancy::findOrFail($id);
        $filename = $vacancy->filename;

        // Send request to Python API to delete the .txt file
        try {
            $fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001');
            $response = Http::delete("{$fastApiUrl}/job-description/{$filename}");

            // We can choose to ignore errors here if the file is already gone,
            // but we'll log a warning if the deletion fails for other reasons.
            if (!$response->successful() && $response->status() !== 404) {
                 return redirect()->route('loker.index')->with('warning', 'Gagal menghapus file deskripsi di server AI, tetapi loker akan tetap dihapus dari database.');
            }

        } catch (\Exception $e) {
            return redirect()->route('loker.index')->with('warning', 'Server AI tidak dapat dihubungi, tetapi loker akan tetap dihapus dari database.');
        }

        // Delete vacancy from Laravel DB
        $vacancy->delete();

        return redirect()->route('loker.index')->with('success', 'Lowongan kerja berhasil dihapus.');
    }

    public function fixVacancyFilename()
    {
        $vacancy = JobVacancy::where('title', 'AI Engineer')->first();
        if ($vacancy) {
            $vacancy->filename = 'ai-engineer.txt';
            $vacancy->save();
            return 'Filename for AI Engineer has been fixed.';
        }
        return 'AI Engineer vacancy not found.';
    }
}
