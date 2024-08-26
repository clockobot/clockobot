<?php

namespace Tests\Feature\Livewire\Modals\TimeEntries;

use App\Livewire\Modals\TimeEntries\DeleteTimeEntryModal;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DeleteTimeEntryModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('time_entries')]
    public function test_users_can_add_a_time_entry(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $work_type = WorkType::factory()->create();

        $time_entry = TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
        ]);

        Livewire::test(DeleteTimeEntryModal::class, [
            'id' => $time_entry->id,
        ])
            ->call('confirmDelete')
            ->assertHasNoErrors()
            ->assertDispatched('refreshTimeEntriesList')
            ->assertDispatched('notify');

        $this->assertCount(0, TimeEntry::get());
    }
}
