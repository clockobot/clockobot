<?php

namespace Tests\Feature\Livewire\Modals\Users;

use App\Livewire\Modals\Users\EditUserModal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class EditUserModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('users')]
    public function test_that_admins_can_edit_a_user(): void
    {
        $client = User::factory()->create(['name' => 'client 1']);

        Livewire::test(EditUserModal::class, [
            'id' => $client->id,
        ])
            ->set('name', 'Best User')
            ->set('email', 'bob@bob.com')
            ->set('locale', 'en')
            ->set('is_admin', 'true')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('refreshUsersList');
    }
}
