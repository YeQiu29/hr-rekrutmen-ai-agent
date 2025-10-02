<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobVacancy;

class JobVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobVacancy::create([
            'title' => 'AI Engineer',
            'description' => 'Dibutuhkan seorang AI Engineer yang berpengalaman dalam machine learning dan deep learning.',
            'status' => 'open',
        ]);
    }
}
