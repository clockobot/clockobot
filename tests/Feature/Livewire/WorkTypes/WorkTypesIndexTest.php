<?php

namespace Tests\Feature\Livewire\WorkTypes;

use App\Livewire\WorkTypes\WorkTypesIndex;
use App\Models\Client;
use App\Models\Project;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class WorkTypesIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Group('work_types')]
    public function test_work_types_index_component(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory(10)->create();

        Livewire::test(WorkTypesIndex::class)
            ->assertHasNoErrors();
    }

    #[Group('work_types')]
    public function test_users_can_search_a_task_on_work_types_index(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory(10)->create();
        WorkType::factory()->create([
            'title' => 'titre',
        ]);

        // Is enough to test #[Computed] values
        Livewire::test(WorkTypesIndex::class)
            ->set('query', 'titre')
            ->call('search')
            ->assertHasNoErrors();
    }

    #[Group('work_types')]
    public function test_users_can_add_a_task_on_work_types_index(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();

        // Is enough to test #[Computed] values
        Livewire::test(WorkTypesIndex::class)
            ->set('work_type_title', 'titre')
            ->call('addWorkType')
            ->assertHasNoErrors()
            ->assertDispatched('$refresh')
            ->assertDispatched('notify');
    }
}
