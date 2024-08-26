<?php

namespace Tests\Feature\Livewire\Modals\Projects;

use App\Livewire\Modals\Projects\AddProjectModal;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class AddProjectModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('projects')]
    public function test_users_can_add_a_project(): void
    {
        $client = Client::factory()->create();

        Livewire::test(AddProjectModal::class)
            ->set('client_id', $client->id)
            ->set('title', 'Website dev')
            ->call('addProject')
            ->assertHasNoErrors()
            ->assertDispatched('refreshProjectsList')
            ->assertDispatched('notify');
    }

    #[Group('projects')]
    public function test_users_cannot_add_a_project_without_a_client_id(): void
    {
        Livewire::test(AddProjectModal::class)
            ->set('title', 'Website dev')
            ->call('addProject')
            ->assertHasErrors();
    }

    #[Group('projects')]
    public function test_users_cannot_add_a_project_without_a_title(): void
    {
        $client = Client::factory()->create();

        Livewire::test(AddProjectModal::class)
            ->set('client_id', $client->id)
            ->call('addProject')
            ->assertHasErrors();
    }

    #[Group('projects')]
    public function test_users_cannot_add_a_project_if_deadline_is_not_a_date(): void
    {
        $client = Client::factory()->create();

        Livewire::test(AddProjectModal::class)
            ->set('client_id', $client->id)
            ->set('title', 'Website dev')
            ->set('deadline', 'hello crash')
            ->call('addProject')
            ->assertHasErrors();
    }

    #[Group('projects')]
    public function test_users_can_add_a_project_deadline_if_is_a_date(): void
    {
        $client = Client::factory()->create();

        Livewire::test(AddProjectModal::class)
            ->set('client_id', $client->id)
            ->set('title', 'Website dev')
            ->set('deadline', '2023-10-10 12:00:00')
            ->call('addProject')
            ->assertHasNoErrors();
    }

    #[Group('projects')]
    public function test_users_cannot_add_a_project_if_hour_estimate_is_not_numeric(): void
    {
        $client = Client::factory()->create();

        Livewire::test(AddProjectModal::class)
            ->set('client_id', $client->id)
            ->set('title', 'Website dev')
            ->set('hour_estimate', 'hello crash')
            ->call('addProject')
            ->assertHasErrors();
    }

    #[Group('projects')]
    public function test_users_cannot_add_a_project_hour_estimate_if_is_numeric(): void
    {
        $client = Client::factory()->create();

        Livewire::test(AddProjectModal::class)
            ->set('client_id', $client->id)
            ->set('title', 'Website dev')
            ->set('hour_estimate', '50')
            ->call('addProject')
            ->assertHasNoErrors();
    }
}
