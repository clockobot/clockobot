<?php

namespace Tests\Feature\API;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Group('api_dashboard')]
    public function test_get_hours_counts(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();

        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();

        TimeEntry::factory(3)->create(['start' => now()->subMonth()->format('Y-m-d 00:00:00'), 'end' => now()->subMonth()->format('Y-m-d 01:00:00')]);
        TimeEntry::factory(2)->create(['start' => now()->subWeek()->format('Y-m-d 00:00:00'), 'end' => now()->subWeek()->format('Y-m-d 01:00:00')]);
        TimeEntry::factory(1)->create(['start' => now()->format('Y-m-d 02:00:00'), 'end' => now()->format('Y-m-d 04:00:00')]);

        $response = $this->json('GET', '/api/dashboard/hours');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success' => [
                'monthly_count',
                'weekly_count',
                'daily_count',
            ],
        ]);

        $response->assertJson(['success' => ['monthly_count' => 4, 'weekly_count' => 2, 'daily_count' => 2]]);

    }

    #[Group('api_dashboard')]
    public function test_get_latest_work(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();
        TimeEntry::factory(8)->create(['start' => now()->format('Y-m-d 02:00:00'), 'end' => now()->format('Y-m-d 04:00:00')]);

        $response = $this->json('GET', '/api/dashboard/works');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'user_id',
                    'client_id',
                    'work_type_id',
                    'project_id',
                    'start',
                    'end',
                ],
            ],
        ]);
    }

    #[Group('api_dashboard')]
    public function test_get_latest_activities(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();
        TimeEntry::factory(5)->create(['start' => now()->format('Y-m-d 02:00:00'), 'end' => now()->format('Y-m-d 04:00:00')]);

        $response = $this->json('GET', '/api/dashboard/activities');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'user_id',
                    'client_id',
                    'work_type_id',
                    'project_id',
                    'start',
                    'end',
                ],
            ],
        ]);
    }
}
