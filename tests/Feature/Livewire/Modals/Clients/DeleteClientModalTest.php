<?php

namespace Tests\Feature\Livewire\Modals\Clients;

use App\Livewire\Modals\Clients\DeleteClientModal;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DeleteClientModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('clients')]
    public function test_users_can_add_a_client(): void
    {
        $client = Client::factory()->create();

        Livewire::test(DeleteClientModal::class, [
            'id' => $client->id,
        ])
            ->call('confirmDelete')
            ->assertHasNoErrors()
            ->assertDispatched('notify')
            ->assertDispatched('refreshClientsList');
    }
}
