<?php

namespace Tests\Feature\Livewire\Modals\Projects;

use App\Livewire\Modals\Projects\DeleteProjectModal;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DeleteProjectModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('projects')]
    public function test_users_can_delete_a_project(): void
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create([
            'client_id' => $client->id,
        ]);

        Livewire::test(DeleteProjectModal::class, [
            'id' => $project->id,
        ])
            ->call('confirmDelete')
            ->assertHasNoErrors()
            ->assertDispatched('refreshProjectsList')
            ->assertDispatched('notify');
    }
}
