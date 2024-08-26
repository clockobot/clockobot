<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Database\Seeder;

class TimeEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimeEntry::factory(1)
            ->create()
            ->each(function ($time_entry) {
                $client_id = Client::inRandomOrder()->first()->id;
                $project_id = Project::where('client_id', $client_id)->inRandomOrder()->first();
                $user_id = User::inRandomOrder()->first()->id;

                if (is_null($project_id)) {
                    Project::factory(1)->create(['client_id' => $client_id]);
                    $project_id = Project::where('client_id', $client_id)->inRandomOrder()->first();
                }

                $work_type_id = WorkType::inRandomOrder()->first()->id;

                $time_entry->client_id = $client_id;
                $time_entry->project_id = ($project_id) ? $project_id->id : 1;
                $time_entry->user_id = $user_id;
                $time_entry->work_type_id = $work_type_id;
                $time_entry->save();
            });
    }
}
