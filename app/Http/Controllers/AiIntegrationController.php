<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Import Http facade
use Illuminate\Support\Facades\Storage; // Import Storage facade
use App\Models\User; // Import User model
use App\Models\ApplicantProfile; // Import ApplicantProfile model

class AiIntegrationController extends Controller
{
    public function processCv(Request $request)
    {
        try {
            $request->validate([
                'job_description_name' => 'required|string',
            ]);

            $jobDescriptionName = $request->input('job_description_name');

            $vacancy = \App\Models\JobVacancy::where('filename', $jobDescriptionName)->first();

            if (!$vacancy) {
                return response()->json(['error' => 'Job vacancy not found.'], 404);
            }

            $applicants = \App\Models\User::where('role', 'pelamar')
                ->whereHas('applicantProfile', function ($query) use ($vacancy) {
                    $query->where('position_applied', $vacancy->title)
                          ->whereNotNull('cv_path')
                          ->where('cv_path', '!=', '');
                })
                ->get();

            if ($applicants->isEmpty()) {
                return response()->json(['message' => 'No applicants with CVs found for this vacancy.'], 200);
            }

            // Clear previous results for this specific user and vacancy before starting a new batch
            \App\Models\CvAnalysisResult::where('job_vacancy_id', $vacancy->id)
                ->whereIn('user_id', $applicants->pluck('id'))->delete();

            $batch = \Illuminate\Support\Facades\Bus::batch([])->name("Analyze CVs for Vacancy: {$vacancy->id}")->dispatch();

            foreach ($applicants as $applicant) {
                $batch->add(new \App\Jobs\ProcessCvAnalysis($applicant, $vacancy));
            }

            return response()->json(['batch_id' => $batch->id]);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error in processCv: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
    public function getBatchResults(Request $request, $batchId)
    {
        $batch = \Illuminate\Support\Facades\Bus::findBatch($batchId);

        if (!$batch) {
            return response()->json(['error' => 'Batch not found.'], 404);
        }

        $results = \App\Models\CvAnalysisResult::where('batch_id', $batchId)
            ->with('user:id,name') // Eager load user name
            ->get();

        $sortedResults = $results->sortByDesc(function($item) {
            // Ensure result is an array and the key exists before accessing
            $resultArray = is_array($item->result) ? $item->result : [];
            return $resultArray['skor_kecocokan_persen'] ?? 0;
        });

        return response()->json([
            'batch_progress' => [
                'total_jobs' => $batch->totalJobs,
                'processed_jobs' => $batch->processedJobs(),
                'failed_jobs' => $batch->failedJobs,
                'finished' => $batch->finished(),
            ],
            'results' => $sortedResults->values()->all(),
        ]);
    }
}