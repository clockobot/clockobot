<?php

namespace Tests\Feature\Livewire\Modals\Clients;

use App\Livewire\Modals\Clients\AddClientModal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class AddClientModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('clients')]
    public function test_users_can_add_a_client(): void
    {
        Livewire::test(AddClientModal::class)
            ->set('name', 'Client 1')
            ->set('contact_name', 'contact name')
            ->set('contact_email', 'contact@email.com')
            ->set('contact_phone', 'contact_phone')
            ->call('addClient')
            ->assertHasNoErrors()
            ->assertDispatched('refreshClientsList');
    }

    #[Group('clients')]
    public function test_users_cannot_add_a_client_if_name_is_missing(): void
    {
        Livewire::test(AddClientModal::class)
            ->set('contact_name', 'contact name')
            ->set('contact_email', 'contact@email.com')
            ->set('contact_phone', 'contact_phone')
            ->call('addClient')
            ->assertHasErrors();
    }

    #[Group('clients')]
    public function test_users_cannot_add_a_client_if_name_email_is_invalid(): void
    {
        Livewire::test(AddClientModal::class)
            ->set('name', 'Client 1')
            ->set('contact_name', 'contact name')
            ->set('contact_email', 'email')
            ->set('contact_phone', 'contact_phone')
            ->call('addClient')
            ->assertHasErrors();
    }

    #[Group('clients')]
    public function test_users_can_add_a_client_and_only_name_is_required(): void
    {
        Livewire::test(AddClientModal::class)
            ->set('name', 'Client 1')
            ->call('addClient')
            ->assertHasNoErrors();
    }
}
