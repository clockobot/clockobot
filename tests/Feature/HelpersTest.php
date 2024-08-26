<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

/* EXCLUDED in the phpunit config file for now */
class HelpersTest extends TestCase
{
    use RefreshDatabase;

    #[Group('helpers')]
    public function test_process_hours_total_function(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();
        $timeEntries = TimeEntry::factory()->count(3)->make([
            'start' => now()->subHours(1),
            'end' => now(),
        ]);

        $total = process_hours_total($timeEntries);

        $this->assertEquals('3:00', $total); // 3 entries each with 1 hour, so total is 3 hours
    }

    #[Group('helpers')]
    public function test_export_time_entries(): void
    {
        Excel::fake();

        $timeEntries = TimeEntry::factory()->count(5)->make();

        export_time_entries($timeEntries);

        Excel::assertDownloaded('report.xlsx');
    }
}
