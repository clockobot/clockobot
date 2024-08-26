<?php

namespace Tests\Feature\Livewire\Modals\Clients;

use App\Livewire\Modals\Clients\EditClientModal;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class EditClientModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('clients')]
    public function test_users_can_edit_a_client(): void
    {
        $client = Client::factory()->create(['name' => 'client 1']);

        Livewire::test(EditClientModal::class, [
            'id' => $client->id,
        ])
            ->set('name', 'Best Client')
            ->set('contact_name', null)
            ->set('contact_email', null)
            ->set('contact_phone', null)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('refreshClientsList');
    }

    #[Group('clients')]
    public function test_a_client_has_a_projects_relation(): void
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create([
            'client_id' => $client,
        ]);

        $this->assertEquals($client->projects()->first()->title, $project->title);
    }
}
