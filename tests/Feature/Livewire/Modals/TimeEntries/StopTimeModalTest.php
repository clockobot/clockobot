<?php

namespace Tests\Feature\Livewire\Modals\TimeEntries;

use App\Livewire\Modals\TimeEntries\StopTimeModal;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class StopTimeModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('time_entries')]
    public function test_users_can_open_the_stop_time_entry_modal(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();

        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'end' => null,
        ]);

        Livewire::test(StopTimeModal::class)
            ->assertHasNoErrors();

        $this->assertEquals(StopTimeModal::modalMaxWidth(), '5xl');
        $this->assertEquals(StopTimeModal::closeModalOnEscape(), true);
    }

    #[Group('time_entries')]
    public function test_users_cannot_open_the_stop_time_entry_modal_if_no_ongoing_time_entry(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();

        Livewire::test(StopTimeModal::class)
            ->assertHasNoErrors()
            ->assertStatus(200); // because of redirect back to referer
    }

    #[Group('time_entries')]
    public function test_users_can_open_the_stop_time_entry_modal_and_update_ongoing_entry(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $work_type = WorkType::factory()->create();

        TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
            'end' => null,
            'start' => now()->subHour()->format('Y-m-d H:i'),
        ]);

        Livewire::test(StopTimeModal::class)
            ->set('end_date', now()->format('Y-m-d'))
            ->set('end_time', now()->format('H:i'))
            ->call('addTimeEntry')
            ->assertDispatched('refreshTimeTrigger')
            ->assertDispatched('refreshTimeEntriesList')
            ->assertDispatched('refreshLastFiveEntriesOnDashboard')
            ->assertDispatched('refreshTimeEntriesOnReportingIndex')
            ->assertDispatched('refreshProjectTimeEntries')
            ->assertHasNoErrors()
            ->assertStatus(200);
    }

    #[Group('time_entries')]
    public function test_users_can_open_the_stop_time_entry_modal_and_delete_ongoing_entry(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $work_type = WorkType::factory()->create();

        TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
            'end' => null,
            'start' => now()->subHour()->format('Y-m-d H:i'),
        ]);

        Livewire::test(StopTimeModal::class)
            ->call('deleteTimeEntry')
            ->assertHasNoErrors()
            ->assertStatus(200);

        $this->assertCount(0, TimeEntry::get());
    }
}
