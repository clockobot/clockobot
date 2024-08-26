<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Group('dashboard')]
    public function test_users_get_correct_figures_for_hour_counts(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $work_type = WorkType::factory()->create();

        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'start' => now()->subHours(2)->format('Y-m-d H:i:s'),
            'end' => now()->subHour()->format('Y-m-d H:i:s'),
            'billable' => 1,
        ]);

        $user->refresh();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.dashboard');

        $response->assertSee('1.00');
        $response->assertSee('hour(s)');
        $response->assertSee($client->name);
        $response->assertSee($project->title);
        $response->assertSee($work_type->title);
        $response->assertSee(now()->subHours(2)->format('d.m.Y'));
        $response->assertSee(now()->subHour()->format('d.m.Y'));

    }
}
