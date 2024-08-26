<?php

namespace Tests\Feature\Livewire\Modals\Users;

use App\Livewire\Modals\Users\DeleteUserModal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DeleteUserModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('users')]
    public function test_admins_can_delete_a_user(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $user2 = User::factory()->create(['is_admin' => false]);

        Livewire::test(DeleteUserModal::class, [
            'id' => $user2->id,
        ])
            ->call('confirmDelete')
            ->assertHasNoErrors()
            ->assertHasNoErrors()
            ->assertDispatched('refreshUsersList')
            ->assertDispatched('notify');
    }

    #[Group('users')]
    public function test_that_users_cannot_delete_their_own_account(): void
    {
        $user = $this->createLoggedUser();

        Livewire::test(DeleteUserModal::class, [
            'id' => $user->id,
        ])
            ->call('confirmDelete')
            ->assertDispatched('notify', function ($event, $data) {
                return isset($data[0]['type'], $data[0]['message']) &&
                    $data[0]['type'] === 'error' &&
                    $data[0]['message'] === 'You cannot delete your own account.';
            })
            ->assertHasNoErrors();
    }
}
