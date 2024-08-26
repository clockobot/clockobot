<?php

namespace Tests\Feature\Livewire\Modals\Users;

use App\Livewire\Modals\Users\AddUserModal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class AddUserModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('users')]
    public function it_can_add_a_new_user(): void
    {
        Livewire::test(AddUserModal::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('locale', 'en')
            ->set('is_admin', true)
            ->call('addUser')
            ->assertHasNoErrors()
            ->assertDispatched('refreshUsersList')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'locale' => 'en',
            'is_admin' => true,
        ]);
    }

    #[Group('users')]
    public function test_that_it_validates_required_fields(): void
    {
        Livewire::test(AddUserModal::class)
            ->set('name', '')
            ->set('email', 'not-an-email')
            ->set('locale', '')
            ->set('is_admin', 'not-a-boolean')
            ->call('addUser')
            ->assertHasErrors([
                'name' => 'required',
                'email' => 'email',
                'locale' => 'required',
            ]);
    }

    #[Group('users')]
    public function test_that_it_sends_password_reset_link_on_user_creation(): void
    {
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => 'john@example.com'])
            ->andReturn(Password::RESET_LINK_SENT);

        Livewire::test(AddUserModal::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('locale', 'en')
            ->set('is_admin', false)
            ->call('addUser')
            ->assertHasNoErrors()
            ->assertDispatched('refreshUsersList')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'is_admin' => false,
        ]);
    }

    #[Group('users')]
    public function test_that_it_handles_failed_password_reset_link(): void
    {
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => 'john@example.com'])
            ->andReturn(Password::INVALID_USER);

        Livewire::test(AddUserModal::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('locale', 'en')
            ->set('is_admin', false)
            ->call('addUser')
            ->assertHasNoErrors()
            ->assertDispatched('refreshUsersList')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }
}
