<?php

namespace Tests\Feature\Livewire\Projects;

use App\Livewire\Projects\ProjectsIndex;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ProjectsIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Group('projects')]
    public function test_projects_index_component(): void
    {
        $client = Client::factory()->create();

        Project::factory()->create([
            'client_id' => $client->id,
        ]);

        Livewire::test(ProjectsIndex::class)
            ->assertHasNoErrors()
            ->assertSet('query', '')
            ->assertSet('projects', Project::orderBy('title', 'asc')->paginate(15));
    }

    #[Group('projects')]
    public function test_users_can_search_projects_on_index_component(): void
    {
        $client = Client::factory()->create();

        Project::factory()->create([
            'client_id' => $client->id,
            'title' => 'project 1',
        ]);

        Project::factory()->create([
            'client_id' => $client->id,
            'title' => 'project 2',
        ]);

        Livewire::test(ProjectsIndex::class)
            ->set('query', 'project 1')
            ->call('search')
            ->assertHasNoErrors();

        Livewire::test(ProjectsIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'project')
            ->assertCount('projects', 2);

        Livewire::test(ProjectsIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'project 2')
            ->assertCount('projects', 1);

        Livewire::test(ProjectsIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'project 3')
            ->assertCount('projects', 0);
    }

    #[Group('projects')]
    public function test_a_project_has_a_client_relation(): void
    {
        $client = Client::factory()->create();

        $project = Project::factory()->create([
            'client_id' => $client->id,
            'title' => 'project 1',
        ]);

        $this->assertEquals($client->name, $project->client->name);
    }

    #[Group('projects')]
    public function test_a_project_returns_the_hour_consumption_if_estimate_exists_even_when_no_entries(): void
    {
        $client = Client::factory()->create();

        $project = Project::factory()->create([
            'client_id' => $client->id,
            'title' => 'project 1',
            'hour_estimate' => 20,
        ]);

        $this->assertEquals(0, $project->hours_consumption());
    }

    #[Group('projects')]
    public function test_a_project_returns_0_if_project_hour_estimate_does_not_exist(): void
    {
        $client = Client::factory()->create();

        $project = Project::factory()->create([
            'client_id' => $client->id,
            'title' => 'project 1',
            'hour_estimate' => null,
        ]);

        $this->assertEquals(0, $project->hours_consumption());
    }

    #[Group('projects')]
    public function test_a_project_returns_the_hour_consumption_if_estimate_and_time_entries_exists(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $work_type = WorkType::factory()->create();

        $project = Project::factory()->create([
            'client_id' => $client->id,
            'title' => 'project 1',
            'hour_estimate' => 10,
        ]);

        TimeEntry::factory()->create([
            'client_id' => $client->id,
            'work_type_id' => $work_type->id,
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start' => now()->subHour()->format('Y-m-d H:i'), // make the entry 1 hour
            'end' => now()->format('Y-m-d H:i'),
        ]);

        $this->assertEquals(10, $project->fresh()->hours_consumption());
    }
}
