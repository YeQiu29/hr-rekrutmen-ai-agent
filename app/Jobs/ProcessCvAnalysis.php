<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\JobVacancy;
use App\Models\CvAnalysisResult;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessCvAnalysis implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $maxExceptions = 1;
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $applicant,
        public JobVacancy $vacancy
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting CV analysis for applicant: {$this->applicant->id} for vacancy: {$this->vacancy->id} with Batch ID: {$this->batch()->id}");

        $analysisResult = CvAnalysisResult::firstOrNew(
            [
                'user_id' => $this->applicant->id,
                'job_vacancy_id' => $this->vacancy->id,
            ]
        );

        $analysisResult->batch_id = $this->batch()->id;
        $analysisResult->status = 'processing';
        $analysisResult->result = null;
        $analysisResult->save();

        try {
            $cvPath = $this->applicant->applicantProfile->cv_path;
            $fastApiUrl = 'https://1a612a40dfef.ngrok-free.app';

            if (!Storage::disk('public')->exists($cvPath)) {
                throw new \Exception("CV file not found at path: {$cvPath}");
            }

            $response = Http::asMultipart()
                ->timeout(280)
                ->post("{$fastApiUrl}/analyze-cv/", [
                    [
                        'name'     => 'cv_file',
                        'contents' => Storage::disk('public')->get($cvPath),
                        'filename' => basename($cvPath)
                    ],
                    [
                        'name'     => 'job_description_name',
                        'contents' => $this->vacancy->filename
                    ]
                ]);

            if ($response->successful()) {
                Log::info("Successfully analyzed CV for applicant: {$this->applicant->id}");
                $analysisResult->status = 'success';
                $analysisResult->result = $response->json();
                $analysisResult->save();
            } else {
                $errorMessage = 'API Error (' . $response->status() . '): ' . $response->body();
                throw new \Exception($errorMessage);
            }
        } catch (Throwable $e) {
            Log::error("Failed CV analysis for applicant: {$this->applicant->id}. Error: {$e->getMessage()}");
            $analysisResult->status = 'failed';
            $analysisResult->result = ['error' => $e->getMessage()];
            $analysisResult->save();
            
            $this->fail($e);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::critical("Job ProcessCvAnalysis has failed permanently for applicant {$this->applicant->id}. Reason: {$exception->getMessage()}");
        
        $result = CvAnalysisResult::firstOrNew(
            [
                'user_id' => $this->applicant->id,
                'job_vacancy_id' => $this->vacancy->id,
            ]
        );

        // Ensure batch_id is set, even on failure
        if ($this->batch()) {
            $result->batch_id = $this->batch()->id;
        }

        $result->status = 'failed';
        $result->result = ['error' => 'Job failed: ' . $exception->getMessage()];
        $result->save();
    }
}
