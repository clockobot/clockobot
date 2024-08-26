<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    #[Group('projects')]
    public function test_project_has_attached_time_entries(): void
    {
        $user = $this->createLoggedUser();
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client]);
        $work_type = WorkType::factory()->create();

        $timeEntry1 = TimeEntry::factory()->create(['client_id' => $client->id, 'project_id' => $project->id, 'user_id' => $user->id, 'work_type_id' => $work_type->id]);
        $timeEntry2 = TimeEntry::factory()->create(['client_id' => $client->id, 'project_id' => $project->id, 'user_id' => $user->id, 'work_type_id' => $work_type->id]);

        $project = $project->fresh();

        $this->assertCount(2, $project->time_entries);

        $this->assertTrue($project->time_entries->contains($timeEntry1));
        $this->assertTrue($project->time_entries->contains($timeEntry2));
    }

    #[Group('projects')]
    public function test_show_screen_displays_the_project(): void
    {
        $this->createLoggedUser();
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client]);
        WorkType::factory()->create();

        $response = $this->get(route('projects.show', $project->id));

        $response->assertStatus(200);

        $response->assertViewIs('projects.show');
        $response->assertViewHas('project', $project);
    }

    #[Group('projects')]
    public function test_users_can_export_time_entries(): void
    {
        $this->createLoggedUser();
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client]);
        WorkType::factory()->create();
        TimeEntry::factory()->count(2)->create(['project_id' => $project->id]);

        $this->mockFunction('export_time_entries', function ($timeEntries) {
            $this->assertCount(2, $timeEntries);

            return 'exported_data';
        });

        $response = $this->get(route('projects.export', $project->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename=report.xlsx');
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    protected function mockFunction($function, $callback)
    {
        $mock = Mockery::mock('alias:'.$function);
        $mock->shouldReceive('__invoke')->andReturnUsing($callback);
        $this->app->instance($function, $mock);
    }
}
