<?php

namespace Tests\Feature\API;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ReportingControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Group('api_reporting')]
    public function testList_returns_time_entries_within_last_week(): void
    {
        $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();

        $withinLastWeekEntry = TimeEntry::factory()->create([
            'start' => now()->subDays(3),
            'end' => now()->subDays(2)->endOfDay(),
        ]);

        $outsideLastWeekEntry = TimeEntry::factory()->create([
            'start' => now()->subWeek()->subDays(1),
            'end' => now()->subWeek()->subDays(1)->endOfDay(),
        ]);

        $response = $this->get('/api/reporting/list');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'client_id',
                    'project_id',
                    'work_type_id',
                    'user_id',
                    'start',
                    'end',
                    'billable',
                    'link',
                    'description',
                ],
            ],
        ]);

        $response->assertJsonFragment(['id' => $withinLastWeekEntry->id]);
        $response->assertJsonMissing(['id' => $outsideLastWeekEntry->id]);
    }

    #[Group('api_reporting')]
    public function test_get_filtered_results_with_valid_parameters(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $work_type = WorkType::factory()->create();

        $matchingTimeEntry = TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
            'start' => now()->subDays(5)->format('Y-m-d H:i'),
            'end' => now()->subDays(4)->format('Y-m-d H:i'),
        ]);

        $response = $this->post('/api/reporting/filter', [
            'client_id' => $matchingTimeEntry->client_id,
            'project_id' => $matchingTimeEntry->project_id,
            'user_id' => $matchingTimeEntry->user_id,
            'work_type_id' => $matchingTimeEntry->work_type_id,
            'start_date' => $matchingTimeEntry->start->format('Y-m-d'),
            'start_time' => $matchingTimeEntry->start->format('H:i'),
            'end_date' => $matchingTimeEntry->end->format('Y-m-d'),
            'end_time' => $matchingTimeEntry->end->format('H:i'),
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment(['id' => $matchingTimeEntry->id]);
    }

    #[Group('api_reporting')]
    public function test_get_filtered_results_with_invalid_parameters(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $work_type = WorkType::factory()->create();

        $matchingTimeEntry = TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
            'start' => now()->subDays(5),
            'end' => now()->subDays(4)->endOfDay(),
        ]);

        $response = $this->post('/api/reporting/filter', [
            'client_id' => 'invalid_client_id',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'error' => [
                'client_id',
            ],
        ]);
    }

    #[Group('api_reporting')]
    public function test_get_export_with_valid_data(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $work_type = WorkType::factory()->create();

        $timeEntry = TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
            'start' => now()->subDays(5),
            'end' => now()->subDays(4)->endOfDay(),
        ])->load('user', 'client', 'project'); // Important here! -> eager load related models

        $fakeData = json_encode(['time_entries' => $timeEntry]); // Replace with actual data structure

        $response = $this->post('/api/reporting/export', [
            'data' => $fakeData,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success']);

        $url = $response->json('success');
        $this->assertIsString($url);
        $this->assertStringStartsWith(url('storage/'), $url);
    }
}
