<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'CTM',
                'matricule' => 'CTM-2024-001',
                'status' => 'active',
            ],
            [
                'name' => 'Supratours',
                'matricule' => 'SPT-2024-002',
                'status' => 'active',
            ],
            [
                'name' => 'ALSA',
                'matricule' => 'ALSA-2024-003',
                'status' => 'active',
            ],
            [
                'name' => 'Ghazala',
                'matricule' => 'GHZ-2024-004',
                'status' => 'active',
            ],
            [
                'name' => 'Pullman du Sud',
                'matricule' => 'PDS-2024-005',
                'status' => 'active',
            ],
            [
                'name' => 'TransGhazala',
                'matricule' => 'TGZ-2024-006',
                'status' => 'inactive',
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
