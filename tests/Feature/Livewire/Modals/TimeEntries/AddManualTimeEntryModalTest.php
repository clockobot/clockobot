<?php

namespace Tests\Feature\Livewire\Modals\TimeEntries;

use App\Livewire\Modals\TimeEntries\AddManualTimeEntryModal;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class AddManualTimeEntryModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('time_entries')]
    public function test_users_can_open_the_add_manual_time_entry_modal(): void
    {
        $user = $this->createLoggedUser();

        Livewire::test(AddManualTimeEntryModal::class)
            ->assertHasNoErrors();

        $this->assertEquals(AddManualTimeEntryModal::modalMaxWidth(), '5xl');
        $this->assertEquals(AddManualTimeEntryModal::closeModalOnEscape(), true);
    }

    #[Group('time_entries')]
    public function test_that_project_list_gets_updated_when_users_select_a_client_in_add_manual_time_entry_modal(): void
    {
        $user = $this->createLoggedUser();

        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        Project::factory()->create(['client_id' => $client1->id]);
        Project::factory()->create(['client_id' => $client2->id]);

        Livewire::test(AddManualTimeEntryModal::class)
            ->set('client_id', $client1->id)
            ->call('updateProjectsList')
            ->assertSet('projects', Project::where('client_id', $client1->id)->orderBy('title', 'asc')->get())
            ->assertHasNoErrors();
    }

    #[Group('time_entries')]
    public function test_users_can_save_a_manual_time_entry_when_requested_inputs_are_present(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $work_type = WorkType::factory()->create();

        Livewire::test(AddManualTimeEntryModal::class)
            ->set('client_id', $client->id)
            ->set('project_id', $project->id)
            ->set('work_type_id', $work_type->id)
            ->call('addManualTimeEntry')
            ->assertHasNoErrors()
            ->assertDispatched('refreshTimeEntriesList')
            ->assertDispatched('notify');

        $this->assertEquals($user->id, TimeEntry::get()->first()->user_id);
        $this->assertEquals(now()->format('Y-m-d H:i'), TimeEntry::get()->first()->start->format('Y-m-d H:i'));
        $this->assertEquals(now()->format('Y-m-d H:i'), TimeEntry::get()->first()->end->format('Y-m-d H:i'));
    }
}
