<?php

namespace Tests\Feature\Livewire\Modals\Projects;

use App\Livewire\Modals\Projects\EditProjectModal;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class EditProjectModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('clients')]
    public function test_users_can_open_the_edit_project_modal(): void
    {
        $client = Client::factory()->create();
        $clients = Client::orderBy('name', 'asc')->get();

        Project::factory()->create([
            'client_id' => $client->id,
            'deadline' => '2023-10-10 12:00:00',
        ]);

        $project = Project::get()->first();

        Livewire::test(EditProjectModal::class, [
            'id' => $project->id,
        ])
            ->assertSet('clients', $clients)
            ->assertSet('project_to_edit', $project)
            ->assertSet('title', $project->title)
            ->assertSet('client_id', $project->client_id)
            ->assertSet('description', $project->description)
            ->assertSet('deadline', $project->deadline)
            ->assertSet('hour_estimate', $project->hour_estimate)
            ->assertHasNoErrors();
    }

    #[Group('clients')]
    public function test_users_can_edit_a_project_if_required_fields_are_present(): void
    {
        $client = Client::factory()->create();
        $client2 = Client::factory()->create();

        Project::factory()->create([
            'client_id' => $client->id,
            'deadline' => '2023-10-10 12:00:00',
        ]);

        $project = Project::get()->first();

        Livewire::test(EditProjectModal::class, [
            'id' => $project->id,
        ])
            ->set('title', 'Test')
            ->set('client_id', $client2->id)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('refreshProjectsList')
            ->assertDispatched('notify');
    }

    #[Group('clients')]
    public function test_users_cannot_edit_a_project_if_required_fields_are_missing(): void
    {
        $client = Client::factory()->create();
        $client2 = Client::factory()->create();

        Project::factory()->create([
            'client_id' => $client->id,
            'deadline' => '2023-10-10 12:00:00',
        ]);

        $project = Project::get()->first();

        Livewire::test(EditProjectModal::class, [
            'id' => $project->id,
        ])
            ->set('title', null)
            ->set('client_id', $client2->id)
            ->call('save')
            ->assertHasErrors();

        Livewire::test(EditProjectModal::class, [
            'id' => $project->id,
        ])
            ->set('title', 'Test')
            ->set('client_id', null)
            ->call('save')
            ->assertHasErrors();
    }
}
