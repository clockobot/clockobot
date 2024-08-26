<?php

namespace Tests\Feature\Livewire\TimeEntries;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class TimeEntriesIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Group('time_entries')]
    public function test_that_it_renders_time_entries_index_component(): void
    {
        $user = $this->createLoggedUser();
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $workType = WorkType::factory()->create();
        $timeEntry = TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'end' => now(),
            'work_type_id' => $workType->id,
            'user_id' => $user->id,
        ]);

        Livewire::test('time-entries.time-entries-index')
            ->assertSee($timeEntry->id)
            ->assertSee($client->name)
            ->assertSee($project->name)
            ->assertSee($workType->name);
    }

    #[Group('time_entries')]
    public function test_that_it_paginates_time_entries_correctly(): void
    {
        $user = $this->createLoggedUser();
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $workType = WorkType::factory()->create();

        TimeEntry::factory()->count(20)->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'end' => now(),
            'work_type_id' => $workType->id,
            'user_id' => $user->id,
        ]);

        Livewire::test('time-entries.time-entries-index')
            ->assertSee($client->name)
            ->assertSee($project->name)
            ->assertSee($workType->name)
            ->assertCount('time_entries', 15)
            ->call('nextPage')
            ->assertSee($client->name)
            ->assertSee($project->name)
            ->assertSee($workType->name);
    }

    #[Group('time_entries')]
    public function test_that_it_refreshes_time_entries_list_when_event_dispatched(): void
    {
        $user = $this->createLoggedUser();
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $workType = WorkType::factory()->create();
        $timeEntry = TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'end' => now(),
            'work_type_id' => $workType->id,
            'user_id' => $user->id,
        ]);

        Livewire::test('time-entries.time-entries-index')
            ->assertSee($timeEntry->id)
            ->dispatch('refreshTimeEntriesList')
            ->assertSee($timeEntry->id);
    }
}
