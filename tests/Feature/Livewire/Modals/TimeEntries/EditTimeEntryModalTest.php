<?php

namespace Tests\Feature\Livewire\Modals\TimeEntries;

use App\Livewire\Modals\TimeEntries\EditTimeEntryModal;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class EditTimeEntryModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('time_entries')]
    public function test_users_can_open_the_edit_time_entry_modal(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $work_type = WorkType::factory()->create();

        $time_entry = TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
        ]);

        Livewire::test(EditTimeEntryModal::class, [
            'id' => $time_entry->id,
        ])
            ->assertHasNoErrors();

        $this->assertEquals(EditTimeEntryModal::modalMaxWidth(), '5xl');
        $this->assertEquals(EditTimeEntryModal::closeModalOnEscape(), true);
    }

    #[Group('time_entries')]
    public function test_users_can_edit_a_time_entry(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $work_type = WorkType::factory()->create();

        $time_entry = TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
        ]);

        Livewire::test(EditTimeEntryModal::class, [
            'id' => $time_entry->id,
        ])
            ->set('description', 'Test 1')
            ->call('editManualTimeEntry')
            ->assertHasNoErrors()
            ->assertDispatched('refreshTimeEntriesList')
            ->assertDispatched('notify');

        $this->assertCount(1, TimeEntry::get());
        $this->assertEquals('Test 1', $time_entry->fresh()->description);
    }

    #[Group('time_entries')]
    public function test_that_project_list_gets_updated_when_users_select_a_client_in_edit_time_entry_modal(): void
    {
        $user = $this->createLoggedUser();

        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        $project = Project::factory()->create(['client_id' => $client1->id]);
        Project::factory()->create(['client_id' => $client2->id]);

        $work_type = WorkType::factory()->create();

        $time_entry = TimeEntry::factory()->create([
            'client_id' => $client1->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
        ]);

        Livewire::test(EditTimeEntryModal::class, [
            'id' => $time_entry->id,
        ])
            ->set('client_id', $client1->id)
            ->call('updateProjectsList')
            ->assertSet('projects', Project::where('client_id', $client1->id)->orderBy('title', 'asc')->get())
            ->assertHasNoErrors();
    }
}
