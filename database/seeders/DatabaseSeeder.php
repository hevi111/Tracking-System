<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $texts = [
            'Maintenance', 'Monthly performance meeting', 'Reverse Engineering', 'Best Practice',
            'Equipment Certification', 'RCM/FMEA/RBM', 'Third Party Service Management', 'Maintenance Master schedule/weekly/daily plan',
            'Work Order And System Planing', 'Bill of Materials', 'Prevent of Maintenance Level #1 #2', 'Root Cause Failure analysis (RCA, RCFA)',
            'Asset Numbering System', 'Computerized Maintenance Management System', 'Critical Spares and Wareh Management', 'Shot Down Management',
            'Standard and Specification Corrective Work', 'Technical Data', 'Equipment History Statement', 'Equipment Challenge', 'Reports', 'Action Plan',
            'training', 'Critical Control Management', 'KPI Reports', 'Inventory', 'Maintenance', 'SOP', 'Capex Project'
        ];

        foreach (['Ciment', 'DMO'] as $type) {
            foreach ($texts as $value) {
                $category = \App\Models\Category::factory()->create([
                    'name' => $value,
                    'type' => $type,
                ]);

                $group = \App\Models\Group::factory([
                    'name' => $value,
                    'category_id' => $category->id,
                ])->create();

                $category->update([
                    'default_group_id' => $group->id,
                ]);
            }
        }

        \App\Models\User::factory()->create([
            'name' => 'bazian',
            'email' => 'bazian@gmail.com',
        ]);
        
    }
}