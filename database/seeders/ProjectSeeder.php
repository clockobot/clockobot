<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory(1)
            ->create()
            ->each(function ($project) {
                $client_id = Client::inRandomOrder()->first()->id;
                $project->client_id = $client_id;
                $project->save();
            });
    }
}
