<?php

namespace Tests\Feature\Livewire\Clients;

use App\Livewire\Clients\ClientsIndex;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ClientsIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Group('clients')]
    public function test_clients_index_component(): void
    {
        Client::factory()->create();

        Livewire::test(ClientsIndex::class)
            ->assertHasNoErrors()
            ->assertSet('query', '')
            ->assertSet('clients', Client::orderBy('name', 'asc')->paginate(15));
    }

    #[Group('clients')]
    public function test_users_can_search_clients_on_index_component(): void
    {
        Client::factory()->create(['name' => 'client 1']);
        Client::factory()->create(['name' => 'client 2']);

        Livewire::test(ClientsIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'client')
            ->assertCount('clients', 2);

        Livewire::test(ClientsIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'client 2')
            ->assertCount('clients', 1);

        Livewire::test(ClientsIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'client 3')
            ->assertCount('clients', 0);
    }
}
